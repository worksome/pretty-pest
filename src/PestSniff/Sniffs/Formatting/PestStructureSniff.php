<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\PestSniff\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

final class PestStructureSniff implements Sniff
{
    /**
     * An array of function names that should be classed as functions that will
     * add a Test to the TestSuite.
     * 
     * @var array<int, string> 
     */
    public array $testFunctions = [
        'test',
        'it',
    ];
    
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

    private function stringIsTestFunction(string $value): bool
    {
        foreach ($this->testFunctions as $testFunction) {
            if (str_starts_with($value, $testFunction)) {
                return true;
            }
        }

        return false;
    }
}