<?php

namespace App\Models;

use Storage;
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
        'cms_ownerIds',
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
        'ui_state' => 'json',
        'cms_ownerIds' => 'json'
    ];

    /**
     * A User Has Many CMS CoSpaces
     *
     * @return HasMany
     */
    public function cmsCoSpaces()
    {
        return CmsCoSpace::whereIn('ownerId', $this->cms_ownerIds)->get();
    }

    /**
     * A User Has Many CmsRecordings
     *
     * @return HasMany
     */
    public function cmsRecordings()
    {
        return $this->hasMany(CmsRecording::class);
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

    /**
     * Check if this User owns a CMS Recording
     *
     * @param CmsRecording $cmsRecording
     * @return bool
     */
    public function ownsRecording(CmsRecording $cmsRecording)
    {
        return in_array($cmsRecording->id, $this->cmsRecordings->pluck('id')->toArray());
    }
}
