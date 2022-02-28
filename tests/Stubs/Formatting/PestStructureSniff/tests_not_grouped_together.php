<?php

it('foo', function () {
    expect(true)->toBeTrue();
});

var_dump('foo');

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

var_dump('foo');