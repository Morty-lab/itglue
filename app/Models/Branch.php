<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branch_information';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'uuid';
    protected $fillable = [
        'user_id',
        'company_id',
        'branch_address',
        'website',
        'phone_number',
        'fax',
        'opening_time',
        'closing_time',
    ];



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }


    public static function add($data)
    {
        return self::create($data);
    }

}
