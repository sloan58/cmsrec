<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'cms_owner_id'
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
     * A User Has Many CMS CoSpaces
     *
     * @param null $spaceId
     * @return bool
     */
    public function myRecordings($spaceId = null)
    {
        return $this->cmsCoSpaces->when($spaceId, function ($query) use ($spaceId) {
                return $query->where('space_id', $spaceId);
            })->map(function ($coSpace) {
            return [
                'spaceName' => $coSpace->name,
                'recordings' => array_map(function ($recording) {
                    return [
                        'baseName' => basename($recording),
                        'sanitizedFilename' => preg_replace("/[^A-Za-z0-9 ]/", '', explode('.', basename($recording))[0]),
                        'url' => urlencode($recording),
                        'fileSize' => bytesToHuman(
                            \Storage::disk('recordings')->size($recording)
                        ),
                        'lastModified' => \Carbon\Carbon::createFromTimestamp(
                            \Storage::disk('recordings')->lastModified(
                                $recording
                            )
                        )->toDayDateTimeString()
                    ];
                }, \Storage::disk('recordings')->files("/$coSpace->space_id"))
            ];
        });
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
}
