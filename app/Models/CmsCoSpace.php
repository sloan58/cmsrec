<?php

namespace App\Models;

use Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CmsCoSpace extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'space_id',
        'name',
        'space_tag'
    ];

    /**
     * The attributes that should be appended to the model.
     *
     * @var array
     */
    protected $appends = [
        'size',
        'rawSize',
        'urlSafeFolder',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'managerIds' => 'json',
    ];

    /**
     * A CMS CoSpace belongs to a User
     */
    public function owners()
    {
        return $this->belongsToMany(User::class, 'cms_co_space_user', 'cms_co_space_id', 'user_id')
            ->withPivot('admin_assigned');
    }

    /**
     * A CmsCoSpace Has Many CmsRecordings
     *
     * @return HasMany
     */
    public function cmsRecordings()
    {
        return $this->hasMany(CmsRecording::class);
    }

    /**
     * Format storage folder for URL's
     *
     * @return string
     */
    public function getUrlSafeFolderAttribute()
    {
        return urlencode($this->space_id);
    }

    /**
     * Return the storage size of a CmsCoSpace
     *
     * @return string
     */
    public function getSizeAttribute()
    {
        return bytesToHuman($this->cmsRecordings()->sum('size'));
    }

    /**
     * Return the raw storage size of a CmsCoSpace
     *
     * @return string
     */
    public function getRawSizeAttribute()
    {
        return $this->cmsRecordings()->sum('size');
    }
}
