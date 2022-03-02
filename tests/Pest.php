<?php

declare(strict_types=1);

use Worksome\PrettyPest\Tests\SniffTestCase;
use Worksome\PrettyPest\Tests\Support\CodeSnifferResult;

uses(SniffTestCase::class)->in('Sniffs');

function checkFile(string $filePath): CodeSnifferResult
{
    return test()->checkFile($filePath);
}

function fixFile(string $filePath): CodeSnifferResult
{
    return test()->fixFile($filePath);
}