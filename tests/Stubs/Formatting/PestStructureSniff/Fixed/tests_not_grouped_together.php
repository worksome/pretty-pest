<?php

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

var_dump('foo');

dataset('foo', ['bar', 'baz', 'boom']);

var_dump('foo');