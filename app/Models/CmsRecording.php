<?php

namespace App\Models;

use URL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CmsRecording extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filename',
        'shared',
        'size',
        'shared',
        'last_modified',
        'cms_co_space_id',
        'user_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'last_modified' => 'datetime',
    ];

    /**
     * The attributes that should be appended to the model.
     *
     * @var array
     */
    protected $appends = [
        'signedRoute',
        'friendlySize',
        'urlSafeFilename',
        'urlSafeFullPath',
        'sanitizedFilename',
    ];

    /**
     * A CmsRecording Belongs To a CmsCoSpace
     *
     * @return BelongsTo
     */
    public function cmsCoSpace()
    {
        return $this->belongsTo(CmsCoSpace::class);
    }

    /**
     * The User that owns this recording
     *
     * @return mixed
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Format the file size in human readable format
     *
     * @return string
     */
    public function getFriendlySizeAttribute()
    {
        return bytesToHuman($this->size);
    }
    /**
     * Format filename for URL's
     *
     * @return string
     */
    public function getUrlSafeFullPathAttribute()
    {
        return $this->cmsCoSpace->urlSafeFolder . '/' . $this->urlSafeFilename;
    }

    /**
     * Format filename for URL's
     *
     * @return string
     */
    public function getUrlSafeFilenameAttribute()
    {
        return urlencode($this->filename);
    }

    /**
     * Remove special characters from
     * the recordings filename
     *
     * @return string|string[]|null
     */
    public function getSanitizedFilenameAttribute()
    {
        return preg_replace("/[^A-Za-z0-9 ]/", '', explode('.', basename($this->filename))[0]);
    }

    /**
     * Return the signed route for this resource
     *
     * @return mixed
     */
    public function getSignedRouteAttribute()
    {
        return URL::signedRoute('recordings.shared', ['cmsRecording' => $this->id]);
    }
}
