<?php

namespace Phobiavr\PhoberLaravelCommon\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Phobiavr\PhoberLaravelCommon\Contracts\SessionScheduleHandlerInterface;
use Phobiavr\PhoberLaravelCommon\Tracing\Tracer;

class HandleSessionSchedule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public readonly array $traceHeaders;

    public function __construct(
        public readonly int $instanceId,
        public readonly string $action,
        public readonly ?int $time = null,
        public readonly ?int $sessionId = null,
        public readonly ?string $startedAt = null,
    ) {
        $this->traceHeaders = Tracer::currentTraceHeaders();
    }

    public function handle(SessionScheduleHandlerInterface $handler): void
    {
        Tracer::withConsumerSpan('HandleSessionSchedule', $this->traceHeaders, function () use ($handler) {
            $handler->handle($this->instanceId, $this->action, $this->time, $this->sessionId, $this->startedAt);
        }, array_filter([
            'session.instance_id' => $this->instanceId,
            'session.action'      => $this->action,
            'session.id'          => $this->sessionId,
        ], static fn($value) => $value !== null));
    }
}
