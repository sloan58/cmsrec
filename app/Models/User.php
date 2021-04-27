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
                'recordings' => array_map(function ($recording) use ($coSpace) {
                    return [
                        'space_id' => $coSpace->space_id,
                        'spaceName' => $coSpace->name,
                        'baseName' => basename($recording),
                        'sanitizedFilename' => preg_replace("/[^A-Za-z0-9 ]/", '', explode('.', basename($recording))[0]),
                        'urlSafeFilename' => urlencode(basename($recording)),
                        'fileSize' => bytesToHuman(
                            Storage::disk('recordings')->size($recording)
                        ),
                        'lastModified' => Carbon::createFromTimestamp(
                            Storage::disk('recordings')->lastModified(
                                $recording
                            )
                        )->toDayDateTimeString()
                    ];
                }, array_filter(
                    Storage::disk('recordings')->files(
                        "/{$coSpace->space_id}"
                    ),
                    function ($file) {
                        return 0 !== strpos(basename($file), '.');
                    }
                ))
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
