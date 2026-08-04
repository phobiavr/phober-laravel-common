<?php

namespace Phobiavr\PhoberLaravelCommon\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Phobiavr\PhoberLaravelCommon\Contracts\SessionCancelHandlerInterface;
use Phobiavr\PhoberLaravelCommon\Tracing\Tracer;

class CancelSession implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var array<string, string> */
    public readonly array $traceHeaders;

    public function __construct(public readonly int $sessionId)
    {
        $this->traceHeaders = Tracer::currentTraceHeaders();
    }

    public function handle(SessionCancelHandlerInterface $handler): void
    {
        Tracer::withConsumerSpan('CancelSession', $this->traceHeaders, function () use ($handler) {
            $handler->handle($this->sessionId);
        }, [
            'session.id' => $this->sessionId,
        ]);
    }
}
