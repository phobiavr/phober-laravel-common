<?php

namespace Phobiavr\PhoberLaravelCommon\Exceptions;

use RuntimeException;

/**
 * Thrown when a schedule action can't proceed because the instance already
 * has an active schedule of a different, non-queued type (e.g. maintenance,
 * reservation, an in-progress session). Callers must not retry this — the
 * conflicting schedule won't resolve itself.
 */
class ScheduleConflictException extends RuntimeException {}
