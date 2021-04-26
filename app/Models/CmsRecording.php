<?php

namespace App\Models;

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
        'is_shared',
        'cms_co_space_id',
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
}
