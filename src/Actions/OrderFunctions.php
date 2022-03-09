<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\Actions;

use Worksome\PrettyPest\Contracts\Fixer;
use Worksome\PrettyPest\Support\FunctionDetail;

final class OrderFunctions
{
    /**
     * @param array<int, array<int, string>> $functionOrder
     */
    public function __construct(private Fixer $fixer, private array $functionOrder)
    {
    }

    public function __invoke(): void
    {
        $functions = $this->fixer->getFunctions();
        $sortedFunctions = $this->sortFunctions($functions);

        if (count($functions) === 0) {
            return;
        }

        if ($sortedFunctions === $functions) {
            return;
        }

        $shouldFix = $this->fixer->addError(
            0,
            'Functions in Pest files must be in the specified order.'
        );

        if ($shouldFix === false) {
            return;
        }

        /** @var FunctionDetail $function */
        foreach ($functions as $function) {
            $this->fixer->deleteFunction($function);
        }

        $sortedFunctionContents = implode(
            PHP_EOL . PHP_EOL,
            array_map(fn (FunctionDetail $detail) => $detail->getContents(), $sortedFunctions),
        );

        $this->fixer->insertContent($sortedFunctionContents, $functions[0]->getStartPtr() - 1);
    }

    private function sortFunctions(array $functions): array
    {
        usort(
            $functions,
            fn (FunctionDetail $a, FunctionDetail $b) => $this->getFunctionPriority($b) <=> $this->getFunctionPriority($a),
        );

        return $functions;
    }

    private function getFunctionPriority(FunctionDetail $functionDetail): int
    {
        // We'll flip the order array so that the indexes correctly represent priority.
        $order = array_reverse($this->functionOrder);

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

}