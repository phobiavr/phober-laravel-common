<?php

namespace Phobiavr\PhoberLaravelCommon\Exceptions;

use RuntimeException;

/**
 * Thrown when a schedule action targets an instance id that no longer
 * exists. Callers must not retry this — a missing instance won't resolve
 * itself.
 */
class InstanceNotFoundException extends RuntimeException {}
