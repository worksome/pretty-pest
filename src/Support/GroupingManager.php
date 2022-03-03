<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\Support;

use Worksome\PrettyPest\Contracts\Fixer;

final class GroupingManager
{
    private FunctionManager $functionManager;

    public function __construct(private Fixer $fixer, private array $functionsInGroup)
    {
        $this->functionManager = new FunctionManager($this->fixer);
    }

    public function check(): self
    {
        $functionCalls = $this->functionManager->all();
        $nextFunctionCallMustBePartOfGroup = false;
        $shouldFix = false;

        /* @var FunctionDetail $functionDetail */
        foreach ($functionCalls as $index => $functionDetail) {
            if (in_array($functionDetail->getName(), $this->functionsInGroup)) {
                $nextFunctionCallMustBePartOfGroup = true;
                continue;
            }

            if ($nextFunctionCallMustBePartOfGroup === false) {
                continue;
            }

            if (! $this->thereAreMoreFunctionsFromGroup($functionCalls, $index)) {
                break;
            }

            $shouldFix = $this->fixer->addError(
                $functionDetail->getEndPtr(),
                "[{$this->functionsInGroupAsString()}] functions should be grouped together",
            );

            $nextFunctionCallMustBePartOfGroup = false;
        }

        if ($shouldFix) {
            $this->reorderFunctionsInGroup();
        }

        return $this;
    }

    private function thereAreMoreFunctionsFromGroup(array $functionCalls, int $currentIndex): bool
    {
        $remainingGroupFunctions = array_filter(
            array_slice($functionCalls, $currentIndex + 1),
            fn (FunctionDetail $detail) => in_array($detail->getName(), $this->functionsInGroup),
        );

        return count($remainingGroupFunctions) > 0;
    }

    private function functionsInGroupAsString(): string
    {
        return implode(', ', $this->functionsInGroup);
    }

    private function reorderFunctionsInGroup(): void
    {
        $relevantFunctions = $this->functionManager->named($this->functionsInGroup);
        $groupContents = [];

        /** @var FunctionDetail $functionDetail */
        foreach (array_slice($relevantFunctions, 1) as $functionDetail) {
            $groupContents[] = $functionDetail->getContents();
            $this->fixer->deleteFunction($functionDetail);
        }

        $this->fixer->insertContent(implode(PHP_EOL . PHP_EOL, $groupContents), $relevantFunctions[0]);
    }

}