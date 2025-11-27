<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Exceptions;

use RuntimeException;

class StepTimeoutException extends RuntimeException
{
    public function __construct(string $stepName, int $timeout)
    {
        parent::__construct("Step '{$stepName}' timed out after {$timeout} seconds.");
    }
}
