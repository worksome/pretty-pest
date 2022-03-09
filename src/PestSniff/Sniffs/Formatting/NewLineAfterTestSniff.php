<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\PestSniff\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use Worksome\PrettyPest\Actions\EnforceWhitespace;
use Worksome\PrettyPest\Fixers\SquizLabsFixer;

final class NewLineAfterTestSniff implements Sniff
{
    public function register(): array
    {
        return [T_OPEN_TAG];
    }

    public function process(File $phpcsFile, $stackPtr): void
    {
        (new EnforceWhitespace(new SquizLabsFixer($this, $phpcsFile)))();
    }
}