<?php

namespace Phobiavr\PhoberLaravelCommon;

use Illuminate\Database\Eloquent\Model;

class IdempotencyKey extends Model {
    protected $connection = 'db_shared';

    protected $fillable = ['scope', 'key', 'request_hash', 'response_status', 'response_content_type', 'response_body'];

    public function isPending(): bool {
        return is_null($this->response_status);
    }
}
