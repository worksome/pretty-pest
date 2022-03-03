<?php

dataset('foo', ['foo', 'baz', 'bar']);

dataset('names', ['luke', 'oliver', 'jeremy']);

it('foo', function () {
    expect(true)->toBeTrue();
});

dataset('hello', function () {
    return ['hello', 'world'];
});

it('bar', function () {
    expect(true)->toBeTrue();
});

it('baz', function () {
    expect(true)->toBeTrue();
});

dataset('foo', ['bar', 'baz', 'boom']);

it('boom', function () {
    expect(true)->toBeTrue();
});

var_dump('here');

var_dump('here');