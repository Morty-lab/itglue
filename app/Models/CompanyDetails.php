<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

class CompanyDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'company_id',
        'employees',
        'devices',
        'branches',
        'software_licenses',
        'attachments'
    ];

    // Specify that ID should be treated as a string
    protected $keyType = 'string';

    // Disable auto-incrementing for UUID
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    // Encrypt sensitive fields before saving
    public function setCredentialPasswordAttribute($value)
    {
        $this->attributes['credential_password'] = Crypt::encryptString($value);
    }

    // Decrypt sensitive fields when retrieving
    public function getCredentialPasswordAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            // Log the error or handle decryption failure
            return null;
        }
    }

    // Optional: Method to add record with additional logic
    public static function add(array $data)
    {
        // Additional validation or preprocessing can be done here
        return self::create($data);
    }

    // Relationship with User model (assuming you have a User model)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mutator for attachments to handle multiple file paths
    public function getAttachmentsAttribute($value)
    {
        return $value ? explode(',', $value) : [];
    }

    public function setAttachmentsAttribute($value)
    {
        $this->attributes['attachments'] = is_array($value)
            ? implode(',', $value)
            : $value;
    }
}
