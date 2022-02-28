<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\Tests\Support;

use PHP_CodeSniffer\Files\LocalFile;
use PHPUnit\Framework\Assert;

final class CodeSnifferResult
{
    public function __construct(private SniffFormatter $sniffClass, private LocalFile $result)
    {
    }

    public function assertHasNoErrors(): self
    {
        Assert::assertEmpty($this->result->getErrors());

        return $this;
    }

    public function assertHasErrors(int|array|null $errors = null): self
    {
        if ($errors === null) {
            Assert::assertNotEmpty($this->result->getErrors());

            return $this;
        }

        if (is_array($errors)) {
            foreach ($errors as $line) {
                $this->assertHasError($line);
            }

            return $this;
        }

        Assert::assertCount($errors, $this->result->getErrors());

        return $this;
    }

    public function assertHasError(int $line): self
    {
        $errors = $this->result->getErrors();

        Assert::assertTrue(key_exists($line, $errors), sprintf('Expected error on line %s, but none found.', $line));

        $sniffCode = sprintf('%s.%s', $this->sniffClass->asReference(), $this->sniffClass->asFQCN());

        Assert::assertTrue(
            $this->hasError($errors[$line], $sniffCode),
            sprintf(
                'Expected error %s, but none found on line %d.%sErrors found on line %d:%s%s%s',
                $sniffCode,
                $line,
                PHP_EOL . PHP_EOL,
                $line,
                PHP_EOL,
                $this->formattedErrors($errors[$line]),
                PHP_EOL
            )
        );

        return $this;
    }

    public function assertMatchesFile(string $filePath): self
    {
        Assert::assertSame(file_get_contents($filePath), $this->result->fixer->getContents());

        return $this;
    }

    private function hasError(array $errorsOnLine, string $sniffCode): bool
    {
        $hasError = false;

        foreach ($errorsOnLine as $errorsOnPosition) {
            foreach ($errorsOnPosition as $error) {
                /** @var string $errorSource */
                $errorSource = $error['source'];

                if ($errorSource === $sniffCode) {
                    $hasError = true;
                    break;
                }
            }
        }

        return $hasError;
    }

    private function formattedErrors(array $errors): string
    {
        return implode(PHP_EOL, array_map(static function (array $errors): string {
            return implode(PHP_EOL, array_map(static function (array $error): string {
                return sprintf("\t%s: %s", $error['source'], $error['message']);
            }, $errors));
        }, $errors));
    }
}