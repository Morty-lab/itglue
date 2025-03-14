<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class License extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'software_license',
        'quantity'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }


    /**
     * Add a new software license
     *
     * @param array $data
     * @return License|false
     */
    public static function add(array $data)
    {
        return self::create($data);
    }

    /**
     * Update existing license data
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function updateLicense($id, array $data)
    {
        return self::where('id', $id)->update($data);
    }
}
