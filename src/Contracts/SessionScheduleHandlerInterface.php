<?php

namespace Phobiavr\PhoberLaravelCommon\Contracts;

interface SessionScheduleHandlerInterface {
    public function handle(int     $instanceId,
                           string  $action,
                           ?int    $time,
                           ?int    $sessionId,
                           ?string $startedAt = null): void;
}
