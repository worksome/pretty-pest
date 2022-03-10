<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\Actions;

use Worksome\PrettyPest\Contracts\Fixer;
use Worksome\PrettyPest\Support\FunctionDetail;

final class EnforceWhitespace
{
    /**
     * @param array<int, string> $functionsToEnforceWhitespaceFor
     */
    public function __construct(private Fixer $fixer, private array $functionsToEnforceWhitespaceFor)
    {
    }

    public function __invoke(): void
    {
        $functions = array_filter($this->fixer->getFunctionCalls(), function (FunctionDetail $function) {
            return in_array($function->getName(), $this->functionsToEnforceWhitespaceFor);
        });

        $shouldFix = false;

        /** @var FunctionDetail $function */
        foreach ($functions as $function) {
            $whitespaceDetail = $function->getWhitespaceAfterFunction();

            if ($whitespaceDetail === null) {
                continue;
            }

            if ($whitespaceDetail->isExactlyOneLine()) {
                continue;
            }

            $shouldFix = $this->fixer->addError(
                $function->getEndPtr() - 1,
                'Pest functions should have a new line between each function call.'
            );
        }

        if ($shouldFix === false) {
            return;
        }

        foreach ($functions as $function) {
            $this->fixer->deleteFunctionWhitespace($function);
            $this->fixer->insertContent(PHP_EOL . PHP_EOL, $function->getEndPtr());
        }
    }
}