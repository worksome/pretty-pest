<?php

it('foo', function () {
    expect(true)->toBeTrue();
});
test('bar', function () {
    expect(true)->toBeTrue();
});
// Foo bar baz
it('baz', function () {
    expect(true)->toBeTrue();
});

it('boom', function () {
    expect(true)->toBeTrue();
});

function foo(): bool
{
    return false;
}
var_dump('foo');
var_dump('bar');