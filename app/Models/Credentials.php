<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Credentials extends Model
{
    //
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'credential_type',
        'credential_name',
        'credential_url',
        'credential_username',
        'credential_password',
        'credential_mfa',
        'credential_notes',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public static function add(array $data)
    {
        // Additional validation or preprocessing can be done here
        return self::create($data);
    }



}
