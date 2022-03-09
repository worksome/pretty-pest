<?php

declare(strict_types=1);

use Worksome\PrettyPest\Tests\SniffTestCase;
use Worksome\PrettyPest\Tests\Support\CodeSnifferResult;

uses(SniffTestCase::class)->in('Sniffs');

function checkFile(string $filePath, array $properties = []): CodeSnifferResult
{
    return test()->checkFile($filePath, $properties);
}

function fixFile(string $filePath, array $properties = []): CodeSnifferResult
{
    return test()->fixFile($filePath, $properties);
}