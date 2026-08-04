<?php

namespace Tests\Unit\Jobs;

use Illuminate\Contracts\Queue\Job as QueueJob;
use Phobiavr\PhoberLaravelCommon\Contracts\SessionScheduleHandlerInterface;
use Phobiavr\PhoberLaravelCommon\Enums\SessionScheduleActionEnum;
use Phobiavr\PhoberLaravelCommon\Exceptions\ScheduleConflictException;
use Phobiavr\PhoberLaravelCommon\Jobs\HandleSessionSchedule;
use RuntimeException;
use Tests\TestCase;

class HandleSessionScheduleTest extends TestCase
{
    public function test_delegates_to_the_bound_handler_with_all_constructor_arguments(): void
    {
        $handler = $this->createMock(SessionScheduleHandlerInterface::class);
        $handler->expects($this->once())
            ->method('handle')
            ->with(1, SessionScheduleActionEnum::START, 30, 10, '2026-08-04 10:00:00');

        (new HandleSessionSchedule(1, SessionScheduleActionEnum::START, 30, 10, '2026-08-04 10:00:00'))
            ->handle($handler);
    }

    public function test_fails_the_job_without_retrying_when_the_handler_reports_a_schedule_conflict(): void
    {
        $conflict = new ScheduleConflictException('conflict');

        $handler = $this->createMock(SessionScheduleHandlerInterface::class);
        $handler->method('handle')->willThrowException($conflict);

        $queueJob = $this->createMock(QueueJob::class);
        $queueJob->expects($this->once())->method('fail')->with($conflict);

        $job = new HandleSessionSchedule(1, SessionScheduleActionEnum::QUEUE, null, null, null);
        $job->job = $queueJob;

        // No exception should escape — it's swallowed via $this->fail(), not rethrown.
        $job->handle($handler);
    }

    public function test_lets_unrelated_exceptions_propagate_so_the_normal_retry_mechanism_still_applies(): void
    {
        $handler = $this->createMock(SessionScheduleHandlerInterface::class);
        $handler->method('handle')->willThrowException(new RuntimeException('boom'));

        $job = new HandleSessionSchedule(1, SessionScheduleActionEnum::QUEUE, null, null, null);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('boom');

        $job->handle($handler);
    }
}
