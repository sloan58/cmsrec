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
        'ownerId',
    ];

    /**
     * A CMS CoSpace belongs to a User
     *
     * @return BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'ownerId', 'cms_owner_id');
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
     * Get recordings for this space
     *
     * @return mixed
     */
    public function recordings()
    {
        return Storage::disk('recordings')->files($this->space_id);
    }

    /**
     * Return the storage size of a CmsCoSpace
     *
     * @return string
     */
    public function size()
    {
        $size = 0;
        $files = Storage::disk('recordings')->files($this->space_id);
        array_walk($files, function($file) use (&$size) {
            $size += Storage::disk('recordings')->size($file);
        });
        return bytesToHuman($size);
    }
}
