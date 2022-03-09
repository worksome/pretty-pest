<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\Actions;

use Worksome\PrettyPest\Contracts\Fixer;
use Worksome\PrettyPest\Support\FunctionDetail;

final class EnforceWhitespace
{
    public function __construct(private Fixer $fixer)
    {
    }

    public function __invoke(): void
    {
        $functions = $this->fixer->getFunctionCalls();

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

        /** @var FunctionDetail $function */
        foreach ($functions as $function) {
            $this->fixer->deleteFunctionWhitespace($function);
            $this->fixer->insertContent(PHP_EOL . PHP_EOL, $function->getEndPtr() - 1);
        }
    }
}