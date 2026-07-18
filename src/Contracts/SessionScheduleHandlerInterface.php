<?php

namespace Phobiavr\PhoberLaravelCommon\Contracts;

use Phobiavr\PhoberLaravelCommon\Enums\SessionScheduleActionEnum;

interface SessionScheduleHandlerInterface {
    public function handle(int                       $instanceId,
                           SessionScheduleActionEnum $action,
                           ?int                       $time,
                           ?int                       $sessionId,
                           ?string                    $startedAt = null): void;
}
