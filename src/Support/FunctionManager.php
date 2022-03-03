<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\Support;

use Worksome\PrettyPest\Contracts\Fixer;

final class FunctionManager
{
    public function __construct(private Fixer $fixer)
    {
    }

    /**
     * @return array<int, FunctionDetail>
     */
    public function all(): array
    {
        return $this->fixer->getFunctions();
    }

    /**
     * @param array<int, string> $functionNames
     * @return array<int, FunctionDetail>
     */
    public function named(array $functionNames): array
    {
        return array_values(array_filter(
            $this->all(),
            fn (FunctionDetail $detail) => in_array($detail->getName(), $functionNames),
        ));
    }

}