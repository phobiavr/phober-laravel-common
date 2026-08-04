<?php

namespace Tests\Unit\Jobs;

use Phobiavr\PhoberLaravelCommon\Contracts\SessionCancelHandlerInterface;
use Phobiavr\PhoberLaravelCommon\Jobs\CancelSession;
use Tests\TestCase;

class CancelSessionTest extends TestCase
{
    public function test_delegates_to_the_bound_handler_with_the_session_id(): void
    {
        $handler = $this->createMock(SessionCancelHandlerInterface::class);
        $handler->expects($this->once())->method('handle')->with(42);

        (new CancelSession(42))->handle($handler);
    }
}
