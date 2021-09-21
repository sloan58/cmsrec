<?php

namespace App\Models;

use Storage;
use Illuminate\Notifications\Notifiable;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
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
        'ui_state' => 'json',
    ];

    /**
     * A User Has Many CMS CoSpaces
     */
    public function cmsCoSpaces()
    {
        return $this->belongsToMany(CmsCoSpace::class, 'cms_co_space_user', 'user_id','cms_co_space_id' )
            ->withPivot('admin_assigned');
    }

    /**
     * A User Has Many CmsRecordings
     *
     */
    public function cmsRecordings()
    {
        return $this->hasManyDeep('App\Models\CmsRecording', ['cms_co_space_user', 'App\Models\CmsCoSpace']);
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
