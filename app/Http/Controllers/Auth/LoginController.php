<?php

namespace App\Http\Controllers\Auth;

use App\Settings\LdapSettings;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * @var bool|resource
     */
    private $ldapConnection;

    /**
     * @var LdapSettings
     */
    private $ldapSettings;

    /**
     * Create a new controller instance.
     *
     * @param LdapSettings $ldapSettings
     */
    public function __construct(LdapSettings $ldapSettings)
    {
        $this->middleware('guest')->except('logout');

        $this->ldapSettings = $ldapSettings;

        if ($this->ldapSettings->name) {
            $this->ldapConnection = $this->getLdapConnection();
        } else {
            $this->ldapConnection = null;
        }
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request, LdapSettings $ldapSettings)
    {
        $this->validateLogin($request);

        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($user) {
            if ($this->attemptLogin($request)) {
                return $this->sendLoginResponse($request);
            }

            if ($this->ldapConnection) {
                $result = ldap_search(
                    $this->ldapConnection,
                    $this->ldapSettings->searchBase,
                    "(mail=$credentials[email])",
                    ['distinguishedname']
                );

                $userDistinguishedName = ldap_get_entries($this->ldapConnection, $result);

                if (!$userDistinguishedName['count']) {
                    return $this->sendFailedLoginResponse($request, 'These credentials do not match our records');
                }

                ldap_set_option($this->ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($this->ldapConnection, LDAP_OPT_NETWORK_TIMEOUT, 3);
                try {
                    ldap_bind($this->ldapConnection, $userDistinguishedName[0]['distinguishedname'][0], $credentials['password']);
                    Auth::login($user);
                    return $this->sendLoginResponse($request);
                } catch (Exception $e) {
                    $message = substr($e->getMessage(), strrpos($e->getMessage(), ':') + 1);
                }
            }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request, $message ?? '');
    }

    private function getLdapConnection()
    {
        $connection = ldap_connect($this->ldapSettings->host);

        ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($connection, LDAP_OPT_NETWORK_TIMEOUT, 3);

        try {
            ldap_bind(
                $connection,
                $this->ldapSettings->bindDN,
                $this->ldapSettings->password
            );

            return $connection;

        } catch (Exception $e) {
            logger()->error("Ldap@bind: LDAP bind unsuccessful", [$e->getMessage()]);

            return false;
        }
    }
}
