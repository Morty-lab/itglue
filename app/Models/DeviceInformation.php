<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DeviceInformation extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'device_type',
        'other_device_type',
        'device_name',
        'device_username',
        'primary_password',
        'device_ip_address',
        'device_location',
        'additional_passwords',
        'notes',
    ];

    protected $casts = [
        'additional_passwords' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }


    public static function add(array $data): DeviceInformation
    {
        return static::create($data);
    }
}

