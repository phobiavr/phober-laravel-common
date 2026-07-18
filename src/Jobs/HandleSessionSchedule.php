<?php

namespace Phobiavr\PhoberLaravelCommon\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Phobiavr\PhoberLaravelCommon\Contracts\SessionScheduleHandlerInterface;
use Phobiavr\PhoberLaravelCommon\Enums\SessionScheduleActionEnum;

class HandleSessionSchedule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly int $instanceId,
        public readonly SessionScheduleActionEnum $action,
        public readonly ?int $time = null,
        public readonly ?int $sessionId = null,
        public readonly ?string $startedAt = null,
    ) {}

    public function handle(SessionScheduleHandlerInterface $handler): void
    {
        $handler->handle($this->instanceId, $this->action, $this->time, $this->sessionId, $this->startedAt);
    }
}
