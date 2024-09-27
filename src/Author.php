<?php

namespace Phobiavr\PhoberLaravelCommon;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Author extends Model {
    protected $connection = 'db_shared';
    protected $fillable = ["created_by", "last_updated_by", "updated_at"];

    protected $appends = [
        'authorable_created_at', 'authorable_updated_at'
    ];

    public function authorable(): MorphTo {
        return $this->morphTo();
    }

    public function createdBy(): BelongsTo {
        return $this->belongsTo(User::class, "created_by");
    }

    public function updatedBy(): BelongsTo {
        return $this->belongsTo(User::class, "last_updated_by");
    }

    public function getAuthorableCreatedAtAttribute() {
        return $this->authorable->created_at ?? null;
    }

    public function getAuthorableUpdatedAtAttribute() {
        return $this->authorable->updated_at ?? null;
    }
}
