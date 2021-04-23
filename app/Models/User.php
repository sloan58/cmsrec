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
     * @return bool
     */
    public function myRecordings()
    {
        return $this->cmsCoSpaces->map(function ($coSpace) {
            return [
                'spaceName' => $coSpace->name,
                'recordings' => array_map(function ($recording) {
                    return [
                        'baseName' => basename($recording),
                        'baseNameHash' => \Hash::make(explode('.', basename($recording))[0]),
                        'url' => $recording,
                        'fileSize' => bytesToHuman(
                            \Storage::disk('recordings')->size($recording)
                        ),
                        'lastModified' => sprintf('%s (%s)', \Carbon\Carbon::createFromTimestamp(
                            \Storage::disk('recordings')->lastModified(
                                $recording
                            )
                        )->diffForHumans(), \Carbon\Carbon::createFromTimestamp(
                            \Storage::disk('recordings')->lastModified(
                                $recording
                            )
                        )->toDateTimeString())
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
