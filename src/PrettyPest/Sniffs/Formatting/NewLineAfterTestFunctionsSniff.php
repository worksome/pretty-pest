<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\PrettyPest\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use Worksome\PrettyPest\Actions\EnforceWhitespace;
use Worksome\PrettyPest\Fixers\SquizLabsFixer;

final class NewLineAfterTestFunctionsSniff implements Sniff
{
    public array $functionsToEnforceWhitespaceFor = [
        'uses',
        'beforeAll',
        'beforeEach',
        'afterEach',
        'afterAll',
        'test',
        'it',
        'dataset',
    ];

    public function register(): array
    {
        return [T_OPEN_TAG];
    }

    public function process(File $phpcsFile, $stackPtr): void
    {
        (new EnforceWhitespace(
            new SquizLabsFixer($this, $phpcsFile),
            $this->functionsToEnforceWhitespaceFor
        ))();
    }
}