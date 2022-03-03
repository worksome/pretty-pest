<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\PestSniff\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use Worksome\PrettyPest\Support\Fixers\PhpCs;
use Worksome\PrettyPest\Support\GroupingManager;

final class EnsureFunctionsAreGroupedSniff implements Sniff
{
    public function register(): array
    {
        return [T_STRING];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $fixer = new PhpCs($this, $phpcsFile);

        (new GroupingManager($fixer, ['test', 'it']))->check();
        (new GroupingManager($fixer, ['dataset']))->check();
    }
}