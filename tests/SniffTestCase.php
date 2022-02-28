<?php

declare(strict_types=1);

namespace Worksome\PrettyPest\Tests;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\LocalFile;
use PHP_CodeSniffer\Runner;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHPUnit\Framework\TestCase;
use Worksome\PrettyPest\Tests\Support\CodeSnifferResult;
use Worksome\PrettyPest\Tests\Support\SniffFormatter;

abstract class SniffTestCase extends TestCase
{
    /**
     * The Sniff Fully Qualified Class Name that is being tested.
     *
     * @var class-string|null
     */
    public string|null $sniff = null;

    public function checkFile(string $filePath, array $sniffProperties = []): CodeSnifferResult
    {
        $sniffFormatter = SniffFormatter::fromTestCase($this);
        $file = $this->prepareFile($sniffFormatter, $filePath, $sniffProperties);

        return new CodeSnifferResult($sniffFormatter, $file);
    }

    public function fixFile(string $filePath, array $sniffProperties = []): CodeSnifferResult
    {
        $sniffFormatter = SniffFormatter::fromTestCase($this);
        $file = $this->prepareFile($sniffFormatter, $filePath, $sniffProperties);

        $file->fixer->fixFile();

        return new CodeSnifferResult($sniffFormatter, $file);
    }

    private function prepareFile(
        SniffFormatter $sniffFormatter,
        string $filePath,
        array $sniffProperties = []
    ): LocalFile
    {
        $codeSniffer = new Runner();
        $codeSniffer->config = new Config(array_merge(['-s'], []));
        $codeSniffer->init();

        if (count($sniffProperties) > 0) {
            $codeSniffer->ruleset->ruleset[$sniffFormatter->asReference()]['properties'] = $sniffProperties;
        }

        /** @var Sniff $sniff */
        $sniff = new ($sniffFormatter->asFQCN());

        $codeSniffer->ruleset->sniffs = [$sniffFormatter->asFQCN() => $sniff];

        $codeSniffer->ruleset->populateTokenListeners();

        $file = new LocalFile($filePath, $codeSniffer->ruleset, $codeSniffer->config);
        $file->process();

        return $file;
    }
}