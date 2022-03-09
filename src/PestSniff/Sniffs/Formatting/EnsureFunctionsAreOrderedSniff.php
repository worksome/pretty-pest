<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\PestSniff\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use Worksome\PrettyPest\Actions\OrderFunctions;
use Worksome\PrettyPest\Contracts\Fixer;
use Worksome\PrettyPest\Support\Fixers\SquizLabs;
use Worksome\PrettyPest\Support\FunctionDetail;

final class EnsureFunctionsAreOrderedSniff implements Sniff
{
    public array $order = [
        ['uses'],
        ['test', 'it'],
        ['dataset'],
    ];

    public function register(): array
    {
        return [T_OPEN_TAG];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        (new OrderFunctions(new SquizLabs($this, $phpcsFile), $this->order))();
    }
}