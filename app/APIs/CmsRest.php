<?php


namespace App\ApiClients;

use Exception;
use App\Models\Cms;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\CmsStat;
use App\Models\CmsCoSpace;
use App\Settings\LdapSettings;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

class CmsRest
{
    /**
     * @var Cms
     */
    private $cms;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var LdapSettings
     */
    private $ldapSettings;

    /**
     * @var bool|resource
     */
    private $ldapConnection;

    /**
     * CmsRest constructor.
     *
     * @param Cms $cms
     */
    public function __construct(Cms $cms)
    {
        $this->cms = $cms;

        $this->client = new Client([
            'base_uri' => "https://{$cms->host}:445",
            'verify' => false,
            'timeout' => 10,
            'connect_timeout' => 10,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'auth' => [
                $cms->username,
                $cms->password
            ],
        ]);

        $this->ldapSettings = app(\App\Settings\LdapSettings::class);
        if ($this->ldapSettings->name) {
            $this->ldapConnection = $this->getLdapConnection();
        } else {
            $this->ldapConnection = null;
        }
    }

    /**
     *  Wrapper to query the CMS Api
     *
     * @param $uri
     * @return array|mixed
     * @throws GuzzleException
     */
    private function queryCmsApi($uri)
    {
        logger()->debug("CmsRest@queryCmsApi ({$this->cms->host}): Initiating query", [
            'uri' => $uri
        ]);
        try {
            $response = $this->client->get($uri);
            $xml = simplexml_load_string($response->getBody()->getContents());
            $json = json_encode($xml);

            logger()->debug("CmsRest@queryCmsApi ({$this->cms->host}): Received successful response");
            logger()->debug("CmsRest@queryCmsApi ({$this->cms->host}): JSON response", [
                'response' => $json
            ]);
            return json_decode($json, true);

        } catch (RequestException $e) {
            if ($e instanceof ClientException) {
                logger()->error('Cms@queryCmsApi: Authentication Exception', [$e->getMessage()]);
            } elseif ($e instanceof ConnectException) {
                logger()->error('Cms@queryCmsApi: Connection Exception', [$e->getMessage()]);
            } else {
                logger()->error('Cms@queryCmsApi: Unknown Exception', [$e->getMessage()]);
            }
            die();
        }
    }

    /**
     * @throws GuzzleException
     */
    public function getCoSpaces()
    {
        info("CmsRest@getCoSpaces ({$this->cms->host}): Collecting CMS CoSpaces");

        $offset = 0;
        $limit = 1;
        $now = now();

        info("CmsRest@getCoSpaces ({$this->cms->host}): Set initial query params", [
            'offset' => $offset,
            'limit' => $limit,
        ]);

        $response = $this->queryCmsApi("/api/v1/coSpaces?offset={$offset}&limit={$limit}");

        $total = $response['@attributes']['total'];
        $limit = 20;
        $iterations = (int) ceil((int) $total / $limit);

        info("CmsRest@getCoSpaces ({$this->cms->host}): Calculated query iterations", [
            'total' => $total,
            'limit' => $limit,
            'iterations' => $iterations
        ]);

        info("CmsRest@getCoSpaces ({$this->cms->host}): Iterating API");
        for($i = 1; $i <= $iterations; $i++) {
            $response = $this->queryCmsApi("/api/v1/coSpaces?offset={$offset}&limit={$limit}");
            foreach($response['coSpace'] as $coSpace) {
                logger()->debug("CmsRest@getCoSpaces ({$this->cms->host}): Collecting CoSpace details", [
                    'coSpace' => $coSpace
                ]);
                $response = $this->queryCmsApi("/api/v1/coSpaces/{$coSpace['@attributes']['id']}");

                if(isset($response['name']) && isset($response['ownerId'])) {
                logger()->debug("CmsRest@getCoSpaces ({$this->cms->host}): Updating model");
                    CmsCoSpace::updateOrCreate([
                        'space_id' =>  $response['@attributes']['id']],
                        [
                            'name' => $response['name'],
                            'ownerId' => $response['ownerId']
                        ]
                    )->touch();
                }
            }
            $offset += $limit;
            logger()->debug("CmsRest@getCoSpaces ({$this->cms->host}): Iterating offset", [
                'offset' => $offset
            ]);
        }

        info("CmsRest@queryCmsApi ({$this->cms->host}): Removing stale accounts");
        CmsCoSpace::where('updated_at', '<', $now)->delete();
    }

    /**
     * @throws GuzzleException
     */
    public function getCmsUserIds()
    {
        info("CmsRest@getCmsUserIds ({$this->cms->host}): Collecting CMS User ID's");

        $offset = 0;
        $limit = 1;
        $now = now();

        info("CmsRest@getCmsUserIds ({$this->cms->host}): Set initial query params", [
            'offset' => $offset,
            'limit' => $limit,
        ]);

        $response = $this->queryCmsApi("/api/v1/users?offset={$offset}&limit={$limit}");

        $total = $response['@attributes']['total'];
        $limit = 20;
        $iterations = (int) ceil((int) $total / $limit);

        info("CmsRest@getCmsUserIds ({$this->cms->host}): Calculated query iterations", [
            'total' => $total,
            'limit' => $limit,
            'iterations' => $iterations
        ]);

        info("CmsRest@getCmsUserIds ({$this->cms->host}): Iterating API");
        for($i = 1; $i <= $iterations; $i++) {
            $response = $this->queryCmsApi("/api/v1/users?offset={$offset}&limit={$limit}");
            foreach($response['user'] as $user) {
                logger()->debug("CmsRest@getCmsUserIds ({$this->cms->host}): Collecting User details", [
                    'coSpace' => $user
                ]);
                $response = $this->queryCmsApi("/api/v1/users/{$user['@attributes']['id']}");
                logger()->debug("CmsRest@getCmsUserIds ({$this->cms->host}): Updating model", [
                    'response' => $response
                ]);

                $email = null;
                $dontUpdateName = false;
                if(empty($response['email'])) {
                    $dontUpdateName = true;
                    logger()->debug("CmsRest@getCmsUserIds ({$this->cms->host}): Checking LDAP for user info.  No email available in CMS API response", [
                        $response
                    ]);
                    if ($this->ldapConnection) {
                        foreach($this->ldapSettings->searchBase as $searchbase) {
                            logger()->debug("CmsRest@getCmsUserIds ({$this->cms->host}): Checking $searchbase for user " . $response['name']);
                            $result = ldap_search(
                                $this->ldapConnection,
                                $searchbase,
                                "(sAMAccountName=$response[name])",
                                ['extensionAttribute1']
                            );

                            $userExtensionAttribute1 = ldap_get_entries($this->ldapConnection, $result);

                            logger()->debug("CmsRest@getCmsUserIds ({$this->cms->host}): Received response in $searchbase for user " . $response['name'], [
                                $userExtensionAttribute1
                            ]);

                            if (isset($userExtensionAttribute1['count'])) {
                                if(isset($userExtensionAttribute1[0])) {
                                    if(isset($userExtensionAttribute1[0]['extensionattribute1'])) {
                                        if(isset($userExtensionAttribute1[0]['extensionattribute1'][0])) {
                                            $email = $userExtensionAttribute1[0]['extensionattribute1'][0];
                                            logger()->debug("CmsRest@getCmsUserIds ({$this->cms->host}): Found email in LDAP", [
                                                $email
                                            ]);
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $email = $response['email'];
                }
                if($email) {
                    $localUser = User::whereEmail($email)->first();
                    if($localUser) {
                        $cms_ownerIds = array_unique(array_merge($localUser->cms_ownerIds, [ $response['@attributes']['id'] ]));
                        $name = $dontUpdateName ? $localUser->name : $response['name'];
                    } else {
                        $cms_ownerIds = [ $response['@attributes']['id'] ];
                        $name = $response['name'];
                    }

                    try {
                        User::updateOrCreate(
                            ['email' => $email],
                            [
                                'name' => $name,
                                'cms_ownerIds' => $cms_ownerIds
                            ]
                        )->touch();
                    } catch(Exception $e) {
                        logger()->error('CmsRest@getCmsUserIds: Could not updateOrCreate', [
                            'error' => $e->getMessage(),
                        ]);
                    }
                } else {
                    logger()->debug("CmsRest@getCmsUserIds ({$this->cms->host}): No email available in CMS API or LDAP");
                }
            }

            $offset += $limit;
            logger()->debug("CmsRest@getCmsUserIds ({$this->cms->host}): Iterating offset", [
                'offset' => $offset
            ]);
        }

        info("CmsRest@getCmsUserIds ({$this->cms->host}): Removing stale accounts");
        User::where('cms_ownerIds', '!=', null)->where('updated_at', '<', $now)->delete();
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
