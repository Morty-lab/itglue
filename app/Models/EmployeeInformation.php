<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmployeeInformation extends Model
{
    use HasFactory;

    protected $table = 'employee_details';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'firstname',
        'lastname',
        'employee_email',
        'employee_title',
        'employee_working_location',
        'employee_phone_number',
        'employee_working_number',
        'notes',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }


    public static function add(array $data) {
        return self::create($data);
    }
}

