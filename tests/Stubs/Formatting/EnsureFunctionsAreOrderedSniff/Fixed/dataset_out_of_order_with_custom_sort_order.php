<?php

use PHPUnit\Framework\TestCase;

uses(TestCase::class);

dataset('foo', []);

test('something', function () {
    expect(true)->toBeTrue();
});

it('works as advertised', function () {
    expect(true)->toBeTrue();
});
