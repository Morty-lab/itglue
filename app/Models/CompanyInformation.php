<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CompanyInformation extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'company_name',
        'primary_number',
        'secondary_number',
        'hq_location_name',
        'hq_address',
        'hq_city',
        'hq_state',
        'hq_postal_code',
        'hq_country',
        'hq_province',
        'hq_fax',
        'hq_website',
        'hq_opening_time',
        'hq_closing_time',
        'attachment',
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
        return self::create($data);
    }


}

