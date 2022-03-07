<?php

declare(strict_types=1);

namespace Worksome\Something\Awesome;

use PHPUnit\Framework\TestCase;
use Worksome\PrettyPest\Tests\Support\SniffFormatter;


uses(TestCase::class)->group('foo');

it('does something cool', function () {
    expect(true)->toBeTrue();
});



