<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\PrettyPest\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use Worksome\PrettyPest\Actions\OrderFunctions;
use Worksome\PrettyPest\Fixers\SquizLabsFixer;

final class EnsureFunctionsAreOrderedSniff implements Sniff
{
    public array $order = [
        'uses',
        ['test', 'it'],
        'dataset',
    ];

    public function register(): array
    {
        return [T_OPEN_TAG];
    }

    public function process(File $phpcsFile, $stackPtr): void
    {
        (new OrderFunctions(new SquizLabsFixer($this, $phpcsFile), $this->order))();
    }
}