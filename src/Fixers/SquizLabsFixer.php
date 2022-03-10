<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\Fixers;

use InvalidArgumentException;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use Worksome\PrettyPest\Contracts\Fixer;
use Worksome\PrettyPest\Support\FunctionDetail;
use Worksome\PrettyPest\Support\WhitespaceDetail;

final class SquizLabsFixer implements Fixer
{
    public function __construct(private Sniff $sniff, private File $phpcsFile)
    {
    }

    public function getFunctionCalls(): array
    {
        $calls = [];
        $stackPtr = 0;
        $currentFunctionLocation = $this->phpcsFile->findNext(T_STRING, $stackPtr);

        while ($currentFunctionLocation !== false) {
            $function = $this->getFunction($currentFunctionLocation);

            if ($function !== null) {
                $calls[] = $function;
            }

            $stackPtr = ($function === null ? $stackPtr : $this->findEndOfFunction($currentFunctionLocation)) + 1;
            $currentFunctionLocation = $this->phpcsFile->findNext(T_STRING, $stackPtr);
        }

        return $calls;
    }

    private function getFunction(int $ptr): FunctionDetail|null
    {
        if (!str_contains($this->phpcsFile->getTokensAsString($ptr, 2), '(')) {
            return null;
        }

        if ($this->phpcsFile->findPrevious([T_FUNCTION], $ptr) === $ptr - 2) {
            return null;
        }

        $endOfFunctionPtr = $this->findEndOfFunction($ptr);

        $nextTokenThatIsNotWhitespace = $this->phpcsFile->findNext([T_WHITESPACE], $endOfFunctionPtr + 1, exclude: true);
        $whitespaceDetail = match ($nextTokenThatIsNotWhitespace) {
            false => null,
            default => new WhitespaceDetail($endOfFunctionPtr, $nextTokenThatIsNotWhitespace - 1),
        };

        return new FunctionDetail(
            $this->phpcsFile->getTokensAsString($ptr, 1),
            $ptr,
            $endOfFunctionPtr,
            $this->phpcsFile->getTokensAsString($ptr, $endOfFunctionPtr - $ptr + 1),
            $whitespaceDetail,
        );
    }

    public function addError(int $ptr, string $message): bool
    {
        return $this->phpcsFile->addFixableError($message, $ptr, $this->sniff::class);
    }

    public function deleteFunction(FunctionDetail $functionDetail): void
    {
        $this->deleteFunctionWhitespace($functionDetail);

        foreach (range($functionDetail->getStartPtr(), $functionDetail->getEndPtr()) as $ptrToRemove) {
            $this->phpcsFile->fixer->replaceToken($ptrToRemove, '');
        }
    }

    public function deleteFunctionWhitespace(FunctionDetail $functionDetail): void
    {
        $whitespaceDetail = $functionDetail->getWhitespaceAfterFunction();

        if ($whitespaceDetail === null) {
            return;
        }

        foreach (range($whitespaceDetail->getStartPtr(), $whitespaceDetail->getEndPtr()) as $ptrToRemove) {
            $this->phpcsFile->fixer->replaceToken($ptrToRemove, '');
        }
    }

    public function insertContent(string $content, int $ptr): void
    {
        $this->phpcsFile->fixer->addContent($ptr, $content);
    }

    private function findEndOfFunction(int $ptr): int
    {
        $openingParenthesisCount = 0;
        $closingParenthesisCount = 0;
        $currentPtr = $ptr;

        do {
            $currentPtr = $this->phpcsFile->findNext([T_OPEN_PARENTHESIS, T_CLOSE_PARENTHESIS], $currentPtr + 1);

            if ($currentPtr === false) {
                throw new InvalidArgumentException("The given ptr [{$ptr}] was not the start of a function call.");
            }

            $this->phpcsFile->getTokens()[$currentPtr]['type'] === 'T_OPEN_PARENTHESIS'
                ? $openingParenthesisCount++
                : $closingParenthesisCount++;
        } while (
            $openingParenthesisCount !== $closingParenthesisCount
            || $this->phpcsFile->getTokens()[$currentPtr + 1]['type'] !== 'T_SEMICOLON'
        );

        return $currentPtr + 1;
    }
}