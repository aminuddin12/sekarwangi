<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'chat_group_id',
        'sender_id',
        'receiver_id',
        'reply_id',
        'content',
        'attachment',
        'attachment_type',
        'type',
        'read_at',
        'delivered_at',
        'metadata',
        'is_edited',
        'is_forwarded',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'delivered_at' => 'datetime',
        'metadata' => 'array',
        'is_edited' => 'boolean',
        'is_forwarded' => 'boolean',
    ];

    // Relasi ke Grup (Jika ada)
    public function group()
    {
        return $this->belongsTo(ChatGroup::class, 'chat_group_id');
    }

    // Pengirim
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Penerima (Jika DM)
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Parent Chat (Yang dibalas)
    public function parent()
    {
        return $this->belongsTo(Chat::class, 'reply_id');
    }

    // Balasan untuk chat ini
    public function replies()
    {
        return $this->hasMany(Chat::class, 'reply_id');
    }

    // Status Read (Pivot untuk Grup)
    public function readByUsers()
    {
        return $this->belongsToMany(User::class, 'chat_reads')
                    ->withPivot('read_at');
    }
}
