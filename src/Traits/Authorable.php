<?php

namespace Phobiavr\PhoberLaravelCommon\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;
use Phobiavr\PhoberLaravelCommon\Author;

trait Authorable {
    protected static function bootAuthorable(): void {
        static::created(function ($model) {
            if (Auth::check()) $model->author()->create(['created_by' => Auth::id(), 'last_updated_by' => Auth::id()]);
        });

        static::updated(function ($model) {
            if (Auth::check()) $model->author()->update(['last_updated_by' => Auth::id()]);
        });
    }

    public function author(): MorphOne {
        return $this->morphOne(Author::class, 'authorable');
    }
}
