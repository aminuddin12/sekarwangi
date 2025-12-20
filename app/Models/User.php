<?php

namespace App\Models;

use App\Enums\UserStatus;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method bool hasPermissionTo($permission, $guardName = null)
 * @method bool hasRole($roles, $guardName = null)
 * @method bool hasAnyRole($roles, $guardName = null)
 * @method bool hasAllRoles($roles, $guardName = null)
 * @method bool hasAnyPermission($permissions)
 * @method \Illuminate\Database\Eloquent\Collection getPermissionsViaRoles()
 * @method \Illuminate\Database\Eloquent\Collection getAllPermissions()
 * @method $this assignRole(...$roles)
 * @method $this removeRole($role)
 * @method $this syncRoles(...$roles)
 * @method $this givePermissionTo(...$permissions)
 * @method $this revokePermissionTo($permission)
 * @method $this syncPermissions(...$permissions)
 */
class User extends Authenticatable implements MustVerifyEmail
{
    // HasRoles wajib ada untuk Spatie (hasPermissionTo/hasRole)
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'google_id',
        'google_token',
        'google_refresh_token',
        'avatar',
        'status',
        'is_online',
        'last_seen',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'google_token',
        'google_refresh_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen' => 'datetime',
        'password' => 'hashed',
        'status' => UserStatus::class,
        'is_online' => 'boolean',
    ];

    // --- CORE & ORGANIZATION ---

    public function detail(): HasOne
    {
        return $this->hasOne(UserDetail::class);
    }

    public function divisions(): BelongsToMany
    {
        return $this->belongsToMany(Division::class, 'division_user')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    // --- FINANCE ---

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function recordedFinanceRecords(): HasMany
    {
        return $this->hasMany(FinanceRecord::class, 'recorded_by');
    }

    // --- INVENTORY ---

    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(InventoryLoan::class, 'borrower_id');
    }

    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }

    // --- COMMERCE ---

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function productReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    // --- COMMUNICATION ---

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }

    public function receivedChats(): HasMany
    {
        return $this->hasMany(Chat::class, 'receiver_id');
    }

    public function chatGroups(): BelongsToMany
    {
        return $this->belongsToMany(ChatGroup::class, 'chat_group_users')
                    ->withPivot('is_admin', 'alias', 'is_muted')
                    ->withTimestamps();
    }

    // --- CMS & CONTENT ---

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'author_id');
    }

    public function pageRevisions(): HasMany
    {
        return $this->hasMany(PageRevision::class);
    }

    // --- SYSTEM & ANALYTICS ---

    public function activityLogs()
    {
        return $this->morphMany(SystemActivityLog::class, 'causer');
    }

    public function visitorLogs(): HasMany
    {
        return $this->hasMany(VisitorLog::class);
    }

    public function createdMarketingLinks(): HasMany
    {
        return $this->hasMany(MarketingLink::class, 'created_by');
    }

    // --- HELPERS ---

    public function isActive(): bool
    {
        return $this->status === UserStatus::ACTIVE;
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }
}
