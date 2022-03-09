<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\PestSniff\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use Worksome\PrettyPest\Support\Fixers\SquizLabs;

final class NewLineAfterTestSniff implements Sniff
{
    public function register(): array
    {
        return [T_STRING];
    }

    public function process(File $phpcsFile, $stackPtr): void
    {
        $fixer = new SquizLabs($this, $phpcsFile);
        $test = $fixer->getFunction($stackPtr);

        if ($test === null) {
            return;
        }

        if (! $test->isTest()) {
            return;
        }

        /**
         * Let's now check to see if there is a new line after the test function's ";".
         */
        if (preg_match('/^;\s?\n$/', $phpcsFile->getTokensAsString($test->getEndPtr() - 1, 3)) === 1) {
            return;
        }

        $shouldFix = $fixer->addError(
            $test->getEndPtr(),
            'Pest test functions must be separated by a new line.',
        );

        if ($shouldFix) {
            $phpcsFile->fixer->addNewline($test->getEndPtr());
        }
    }
}