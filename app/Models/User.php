<?php

namespace App\Models;

use Storage;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cms_owner_id',
        'ui_state'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'ui_state' => 'json'
    ];

    /**
     * A User Has Many CMS CoSpaces
     *
     * @return HasMany
     */
    public function cmsCoSpaces()
    {
        return $this->hasMany(CmsCoSpace::class, 'ownerId', 'cms_owner_id');
    }

    /**
     * A User Has Many CmsRecordings Through a CmsCoSpace
     *
     * @return HasManyThrough
     */
    public function cmsRecordings()
    {
        return $this->hasManyThrough(
            CmsRecording::class,
            CmsCoSpace::class,
            'ownerId',
            'cms_co_space_id',
            'cms_owner_id'
        );
    }

    /**
     * Check if the user is an admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return in_array(
            auth()->user()->email,
            env('ADMIN_USERS') ? explode(',', env('ADMIN_USERS')) : [])
            ;
    }

    /**
     * Update the User's UI state data
     *
     * @param $key
     * @param $val
     */
    public function updateUiState($key, $val)
    {
        $newUiState = array_replace(auth()->user()->ui_state ?? [], [$key => $val]);

        auth()->user()->update([
            'ui_state' => $newUiState
        ]);
    }
}
