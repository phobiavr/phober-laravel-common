<?php

namespace Phobiavr\PhoberLaravelCommon\Testing;

/**
 * Local dev DB (not a dedicated test DB) already has real rows for these
 * models, so tests are sensitive to whatever happens to be there. Wiping
 * the models a test file actually touches at the start of each test makes
 * assertions deterministic; it's safe because DatabaseTransactions rolls
 * everything back once the test ends.
 */
trait ClearsExistingRows // @phpstan-ignore trait.unused
{
    protected function clearExistingRows(string ...$modelClasses): void
    {
        foreach ($modelClasses as $modelClass) {
            $modelClass::query()->delete();
        }
    }
}
