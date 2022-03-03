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
            /**
             * If the function is a test, we must make sure that the next function call
             * after it is also a test, so we'll enable that and go to the next call.
             */
            if ($this->isTestFunction($details['functionName'])) {
                $nextFunctionCallShouldBeTest = true;
                continue;
            }

            /**
             * If the next function call doesn't need to be a test,
             * we can safely finish the check here and move on.
             */
            if ($nextFunctionCallShouldBeTest === false) {
                continue;
            }

            /**
             * If there are no more test functions to be called
             * inside the file, we can safely exit the loop.
             */
            if (! $this->thereAreMoreTestFunctions($functionCalls, $index)) {
                break;
            }

            /**
             * If we get to this point then we know that the next function call
             * should be a test function but isn't. We'll add a fixable error
             * on the first line of the next function call.
             */
            $shouldFix = $phpcsFile->addFixableError(
                'Test functions should be grouped together',
                $functionCalls[$index + 1]['startPtr'],
                self::class,
            );

            /**
             * Finally, we'll reset the `$nextFunctionCallShouldBeTest`
             * variable so that we can move on to the next function.
             */
            $nextFunctionCallShouldBeTest = false;
        }

        if (! $shouldFix) {
            return;
        }

        $this->reorderTests($phpcsFile);
    }

    private function reorderTests(File $phpcsFile): void
    {
        $testFunctions = $this->getTestFunctionCalls($phpcsFile);
        $testContents = [];

        foreach (array_slice($testFunctions, 1) as $test) {
            $testContents[] = $this->getFunctionAsString($phpcsFile, $test['startPtr']);
            $this->deleteFunction($phpcsFile, $test['startPtr']);
        }

        $phpcsFile->fixer->addContent(
            $testFunctions[0]['endPtr'],
            PHP_EOL . implode(PHP_EOL . PHP_EOL, $testContents) . PHP_EOL
        );
    }

    private function thereAreMoreTestFunctions(array $functionCalls, int $currentIndex): bool
    {
        $remainingTestFunctions = array_filter(
            array_slice($functionCalls, $currentIndex + 1),
            fn ($detail) => $this->isTestFunction($detail['functionName'])
        );

        return count($remainingTestFunctions) > 0;
    }
}