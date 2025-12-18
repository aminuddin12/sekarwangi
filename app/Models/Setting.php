<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'description',
        'icon',
        'parent_id',
        'order',
        'is_system',
        'is_public',
        'is_encrypted',
        'updated_by',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_public' => 'boolean',
        'is_encrypted' => 'boolean',
        'order' => 'integer',
    ];

    // Accessor: Decrypt value jika is_encrypted true
    public function getValueAttribute($value)
    {
        if ($this->is_encrypted && !empty($value)) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return $value;
            }
        }

        // Cast berdasarkan type
        return match($this->type) {
            'json' => json_decode($value, true),
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'float' => (float) $value,
            default => $value,
        };
    }

    // Mutator: Encrypt value sebelum simpan
    public function setValueAttribute($value)
    {
        if ($this->attributes['is_encrypted'] ?? false) {
            $this->attributes['value'] = Crypt::encryptString($value);
        } else {
            $this->attributes['value'] = is_array($value) ? json_encode($value) : $value;
        }
    }

    public function parent()
    {
        return $this->belongsTo(Setting::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Setting::class, 'parent_id')->orderBy('order');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
