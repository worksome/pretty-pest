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

        foreach ($functionCalls as $index => $details) {
            if ($this->stringIsTestFunction($details['functionName'])) {
                $nextFunctionCallShouldBeTest = true;
                continue;
            }

            if ($nextFunctionCallShouldBeTest === false) {
                continue;
            }

            if ($index === count($functionCalls) - 1) {
                continue;
            }

            $phpcsFile->addFixableError(
                'Test functions should be grouped together',
                $functionCalls[$index + 1]['stackPtr'],
                self::class,
            );

            $nextFunctionCallShouldBeTest = false;
        }
    }
}