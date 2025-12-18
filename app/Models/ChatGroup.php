<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'avatar',
        'description',
        'is_public',
        'only_admins_can_post',
        'created_by'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'only_admins_can_post' => 'boolean',
    ];

    public function participants()
    {
        return $this->belongsToMany(User::class, 'chat_group_users')
                    ->withPivot('is_admin', 'alias', 'is_muted', 'joined_at')
                    ->withTimestamps();
    }

    // Relasi ke Chats (Updated from messages)
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function latestChat()
    {
        return $this->hasOne(Chat::class)->latestOfMany();
    }
}
