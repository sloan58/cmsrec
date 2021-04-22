<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cms extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'host',
        'username',
        'password'
    ];

    /**
     * Decrypt the Password when accessing
     *
     * @param $value
     * @return string
     */
    public function getPasswordAttribute($value)
    {
        return decrypt($value);
    }

    /**
     * Encrypt the Password when setting
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = encrypt($value);
    }
}
