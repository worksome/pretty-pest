<?php

use PHPUnit\Framework\TestCase;

dataset('foo', []);

uses(TestCase::class);

test('something', function () {
    expect(true)->toBeTrue();
});

it('works as advertised', function () {
    expect(true)->toBeTrue();
});
