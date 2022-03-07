<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\PestSniff\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use Worksome\PrettyPest\Contracts\Fixer;
use Worksome\PrettyPest\Support\Fixers\PhpCs;
use Worksome\PrettyPest\Support\FunctionDetail;
use Worksome\PrettyPest\Support\FunctionManager;

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
        $fixer = new PhpCs($this, $phpcsFile);
        $functionManager = new FunctionManager($fixer);
        
        $functions = $functionManager->all();

        if (count($functions) === 0) {
            return;
        }

        $startingPtr = $functions[0]->getStartPtr() - 1;
        
        usort($functions, function (FunctionDetail $a, FunctionDetail $b) use ($fixer) {
            $sortResult = $this->priorityForFunction($b) <=> $this->priorityForFunction($a);

            $this->addSortError($fixer, $b, $sortResult);

            return $sortResult;
        });

        if ($phpcsFile->fixer->enabled === false) {
            return;
        }

        if ($phpcsFile->fixer->loops > 0) {
            return;
        }

        $reorderedFunctions = implode(
            PHP_EOL . PHP_EOL,
            array_map(fn (FunctionDetail $detail) => $detail->getContents(), $functions)
        );

        foreach ($functions as $function) {
            $fixer->deleteFunction($function);
        }

        $fixer->insertContent($reorderedFunctions, $startingPtr);

        return $phpcsFile->numTokens + 1;
    }

    private function priorityForFunction(FunctionDetail $functionDetail): int
    {
        // We'll flip the order array so that the indexes correctly represent priority.
        $order = array_reverse($this->order);

        // By default, we use a low priority so that any unspecified function is placed at the bottom of the file.
        $priority = 0;

        foreach ($order as $index => $group) {
            if (in_array($functionDetail->getName(), $group)) {
                $priority = $index;
                break;
            }
        }

        return $priority;
    }

    private function addSortError(Fixer $fixer, FunctionDetail $functionDetail, int $sortResult): bool
    {
        if ($sortResult === 0) {
            return false;
        }

        return $fixer->addError(
            $functionDetail->getStartPtr(),
            'Functions in Pest files must be in the specified order.'
        );
    }
}