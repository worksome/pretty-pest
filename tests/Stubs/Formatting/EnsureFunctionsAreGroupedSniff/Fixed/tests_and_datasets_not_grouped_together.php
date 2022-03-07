<?php

dataset('foo', ['foo', 'baz', 'bar']);

dataset('names', ['luke', 'oliver', 'jeremy']);

dataset('hello', function () {
    return ['hello', 'world'];
});

dataset('foo', ['bar', 'baz', 'boom']);



it('foo', function () {
    expect(true)->toBeTrue();
});

it('bar', function () {
    expect(true)->toBeTrue();
});

it('baz', function () {
    expect(true)->toBeTrue();
});

it('boom', function () {
    expect(true)->toBeTrue();
});











var_dump('here');

var_dump('here');