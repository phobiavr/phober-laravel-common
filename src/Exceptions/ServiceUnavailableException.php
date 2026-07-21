<?php

namespace Phobiavr\PhoberLaravelCommon\Exceptions;

use RuntimeException;
use Throwable;

/**
 * Thrown when an inter-service HTTP call can't reach its target at all
 * (connection refused, DNS failure, timeout). A non-2xx response from a
 * reachable service is not this — callers keep handling that via the
 * response's own failed()/ok() checks.
 */
class ServiceUnavailableException extends RuntimeException {
    public function __construct(public readonly string $service, ?Throwable $previous = null) {
        parent::__construct("Unable to reach service \"{$service}\".", previous: $previous);
    }
}
