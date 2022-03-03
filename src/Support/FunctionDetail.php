<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\Support;

use PHP_CodeSniffer\Files\File;

final class FunctionDetail
{
    public function __construct(
        private string $name,
        private int $startPtr,
        private int $endPtr,
        private string $contents,
    )
    {
    }

    public static function make(File $phpcsFile, int $stackPtr): self
    {
        $endOfFunctionPtr = $phpcsFile->findEndOfStatement($stackPtr) + 1;

        return new self(
            $phpcsFile->getTokensAsString($stackPtr, 1),
            $stackPtr,
            $endOfFunctionPtr,
            $phpcsFile->getTokensAsString($stackPtr, $endOfFunctionPtr - $stackPtr),
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStartPtr(): int
    {
        return $this->startPtr;
    }

    public function getEndPtr(): int
    {
        return $this->endPtr;
    }

    public function isTest(): bool
    {
        return in_array($this->getName(), ['it', 'test']);
    }

    public function isDataset(): bool
    {
        return $this->getName() === 'dataset';
    }

    public function getContents(): string
    {
        return $this->contents;
    }

}