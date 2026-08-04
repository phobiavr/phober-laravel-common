<?php

namespace Phobiavr\PhoberLaravelCommon\Contracts;

interface SessionCancelHandlerInterface {
    public function handle(int $sessionId): void;
}
