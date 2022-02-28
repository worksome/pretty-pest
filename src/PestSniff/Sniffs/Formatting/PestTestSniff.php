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
                'stackPtr' => $currentFunctionLocation,
                'functionName' => $phpcsFile->getTokensAsString($currentFunctionLocation, 1),
            ];
            $stackPtr = $phpcsFile->findEndOfStatement($currentFunctionLocation);
            $currentFunctionLocation = $phpcsFile->findNext(T_STRING, $stackPtr);
        }

        return $rootFunctionCalls;
    }
}