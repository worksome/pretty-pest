<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\PestSniff\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;

final class TestsAreGroupedTogetherSniff extends PestTestSniff
{
    public function register(): array
    {
        return [T_STRING];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $functionCalls = $this->getRootFunctionCalls($phpcsFile);
        $nextFunctionCallShouldBeTest = false;
        $shouldFix = false;

        foreach ($functionCalls as $index => $details) {
            if ($this->stringIsTestFunction($details['functionName'])) {
                $nextFunctionCallShouldBeTest = true;
                continue;
            }

            if ($nextFunctionCallShouldBeTest === false) {
                continue;
            }


            if (! $this->thereAreMoreTestFunctions($functionCalls, $index)) {
                continue;
            }

            $shouldFix = $phpcsFile->addFixableError(
                'Test functions should be grouped together',
                $functionCalls[$index + 1]['stackPtr'],
                self::class,
            );

            $nextFunctionCallShouldBeTest = false;
        }

        if (! $shouldFix) {
            return;
        }

        $this->reorderTests($phpcsFile, $functionCalls);
    }

    private function reorderTests(File $phpcsFile, array $functionCalls): void
    {

//        $phpcsFile->fixer->replaceToken()
    }

    private function thereAreMoreTestFunctions(array $functionCalls, int $currentIndex): bool
    {
        $remainingTestFunctions = array_filter(
            array_slice($functionCalls, $currentIndex + 1),
            fn ($detail) => $this->stringIsTestFunction($detail['functionName'])
        );

        return count($remainingTestFunctions) > 0;
    }
}