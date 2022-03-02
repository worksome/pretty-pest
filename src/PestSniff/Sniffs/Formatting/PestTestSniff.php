<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\PestSniff\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

abstract class PestTestSniff implements Sniff
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

    protected function stringIsTestFunction(string $value): bool
    {
        foreach ($this->testFunctions as $testFunction) {
            if (str_starts_with($value, $testFunction)) {
                return true;
            }
        }

        return false;
    }

    protected function getRootFunctionCalls(File $phpcsFile): array
    {
        $rootFunctionCalls = [];
        $stackPtr = 0;
        $currentFunctionLocation = $phpcsFile->findNext(T_STRING, $stackPtr);

        while ($currentFunctionLocation !== false) {
            $rootFunctionCalls[] = [
                'startPtr' => $currentFunctionLocation,
                // + 1 includes the semicolon at the end of the function call.
                'endPtr' => $phpcsFile->findEndOfStatement($currentFunctionLocation) + 1,
                'functionName' => $phpcsFile->getTokensAsString($currentFunctionLocation, 1),
            ];
            $stackPtr = $phpcsFile->findEndOfStatement($currentFunctionLocation);
            $currentFunctionLocation = $phpcsFile->findNext(T_STRING, $stackPtr);
        }

        return $rootFunctionCalls;
    }

    protected function getTestFunctionCalls(File $phpcsFile): array
    {
        return array_values(array_filter(
            $this->getRootFunctionCalls($phpcsFile),
            fn ($details) => $this->stringIsTestFunction($details['functionName']),
        ));
    }

    protected function getFunctionAsString(File $phpcsFile, $stackPtr): string
    {
        return $phpcsFile->getTokensAsString($stackPtr, $phpcsFile->findEndOfStatement($stackPtr) - $stackPtr + 1);
    }

    protected function deleteFunction(File $phpcsFile, $stackPtr): void
    {
        // + 1 will include the semicolon at the end of the function.
        $endOfFunctionPtr = $phpcsFile->findEndOfStatement($stackPtr) + 1;

        foreach (range($stackPtr, $endOfFunctionPtr) as $ptrToRemove) {
            $phpcsFile->fixer->replaceToken($ptrToRemove, '');
        }
    }
}