<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Phobiavr\PhoberLaravelCommon\SharedServiceProvider;

abstract class TestCase extends BaseTestCase
{
    /** @return array<int, class-string> */
    protected function getPackageProviders($app): array
    {
        return [
            SharedServiceProvider::class,
        ];
    }
}
