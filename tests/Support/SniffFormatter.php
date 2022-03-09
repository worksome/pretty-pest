<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\Tests\Support;

use InvalidArgumentException;
use Worksome\PrettyPest\Tests\SniffTestCase;

final class SniffFormatter
{

    public function __construct(private string $sniffFQCN)
    {
        if (! class_exists($this->sniffFQCN)) {
            throw new InvalidArgumentException("The given sniff, [{$this->sniffFQCN}], does not exist.");
        }
    }

    public static function fromTestCase(SniffTestCase $testCase): self
    {
        if ($testCase->sniff !== null) {
            return new self($testCase->sniff);
        }

        $relevantClassName = substr($testCase::class, strpos($testCase::class, '\\Sniffs\\') + 1);

        return new self("Worksome\\PrettyPest\\PrettyPest\\" . substr($relevantClassName, 0, strlen($relevantClassName) - 4));
    }

    public function asFQCN(): string
    {
        return $this->sniffFQCN;
    }

    public function asReference(): string
    {
        $dotted = preg_replace(
            ["~\\\\~", '~\.Sniffs~', '~Sniff$~'],
            ['.', '', ''],
            $this->asFQCN()
        );

        return substr($dotted, 20); // After "Worksome.PrettyPest."
    }

}