<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\PestSniff\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;

final class NewLineAfterTestSniff extends PestTestSniff
{
    public function register(): array
    {
        return [T_STRING];
    }

    public function process(File $phpcsFile, $stackPtr): void
    {
        $stringValue = $phpcsFile->getTokensAsString($stackPtr, 1);

        if (! $this->stringIsTestFunction($stringValue)) {
            return;
        }

        $endOfTestFunction = $phpcsFile->findEndOfStatement($stackPtr);

        /**
         * Let's now check to see if there is a new line after the test function.
         */
        if (preg_match('/^;\s?\n$/', $phpcsFile->getTokensAsString($endOfTestFunction, 3)) === 1) {
            return;
        }

        $fix = $phpcsFile->addFixableError(
            'Pest test functions must be separated by a new line.',
            $endOfTestFunction,
            self::class,
        );

        if ($fix === false) {
            return;
        }

        $phpcsFile->fixer->addNewline($endOfTestFunction);
    }
}