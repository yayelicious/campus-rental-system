<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    public const TYPE_ITEM = 'item';

    public const TYPE_USER = 'user';

    public const TYPE_MESSAGE = 'message';

    public const STATUS_PENDING = 'pending';

    public const STATUS_REVIEWED = 'reviewed';

    public const ACTION_DISMISSED = 'dismissed';

    public const ACTION_WARNING = 'warning';

    public const ACTION_ITEM_REMOVED = 'item_removed';

    public const ACTION_ACCOUNT_REMOVED = 'account_removed';

    protected $fillable = [
        'reporter_id',
        'reported_user_id',
        'reported_item_id',
        'reported_message_id',
        'type',
        'reason',
        'details',
        'status',
        'reviewed_by',
        'admin_action',
        'admin_notes',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reportedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    public function reportedItem(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'reported_item_id')->withTrashed();
    }

    public function reportedMessage(): BelongsTo
    {
        return $this->belongsTo(RentalMessage::class, 'reported_message_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function targetUser(): ?User
    {
        if ($this->reportedUser) {
            return $this->reportedUser;
        }

        if ($this->reportedItem?->user) {
            return $this->reportedItem->user;
        }

        return $this->reportedMessage?->sender;
    }
}
