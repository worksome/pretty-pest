<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\Support\Fixers;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use Worksome\PrettyPest\Contracts\Fixer;
use Worksome\PrettyPest\Support\FunctionDetail;

final class SquizLabsFixer implements Fixer
{
    public function __construct(private Sniff $sniff, private File $phpcsFile)
    {
    }

    public function getFunctions(): array
    {
        $calls = [];
        $stackPtr = 0;
        $currentFunctionLocation = $this->phpcsFile->findNext(T_STRING, $stackPtr);

        while ($currentFunctionLocation !== false) {
            $function = $this->getFunction($currentFunctionLocation);

            if ($function !== null) {
                $calls[] = $function;
            }

            $stackPtr = $this->phpcsFile->findEndOfStatement($currentFunctionLocation);
            $currentFunctionLocation = $this->phpcsFile->findNext(T_STRING, $stackPtr);
        }

        return $calls;
    }

    public function getFunction(int $ptr): FunctionDetail|null
    {
        if (! str_contains($this->phpcsFile->getTokensAsString($ptr, 2), '(')) {
            return null;
        }

        $endOfFunctionPtr = $this->phpcsFile->findEndOfStatement($ptr) + 1;

        return new FunctionDetail(
            $this->phpcsFile->getTokensAsString($ptr, 1),
            $ptr,
            $endOfFunctionPtr,
            $this->phpcsFile->getTokensAsString($ptr, $endOfFunctionPtr - $ptr),
        );
    }

    public function addError(int $ptr, string $message): bool
    {
        return $this->phpcsFile->addFixableError($message, $ptr, $this->sniff::class);
    }

    public function deleteFunction(FunctionDetail $functionDetail): void
    {
        $endPtr = $this->phpcsFile->findNext([T_WHITESPACE], $functionDetail->getEndPtr(), exclude: true);

        if ($endPtr === false) {
            $endPtr = $functionDetail->getEndPtr();
        }
        
        foreach (range($functionDetail->getStartPtr(), $endPtr) as $ptrToRemove) {
            $this->phpcsFile->fixer->replaceToken($ptrToRemove, '');
        }
    }

    public function insertContent(string $content, int $ptr): void
    {
        $this->phpcsFile->fixer->addContent($ptr, $content);
    }
}