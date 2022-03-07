<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\Contracts;

use Worksome\PrettyPest\Support\FunctionDetail;

interface Fixer
{
    /**
     * @return array<int, FunctionDetail>
     */
    public function getFunctions(): array;

    public function getFunction(int $ptr): FunctionDetail|null;

    public function addError(int $ptr, string $message): bool;

    public function deleteFunction(FunctionDetail $functionDetail): void;

    public function insertContent(string $content, int $ptr): void;
}