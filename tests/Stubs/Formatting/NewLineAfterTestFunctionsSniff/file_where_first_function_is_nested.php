<?php

function foobar(callable $callable): array
{
    return [
        'foo' => function () use ($callable) {
            var_dump(var_dump('foo'));
        },
        'bar' => var_dump(var_dump('foo')),
    ];
}

dataset('foo', function () {
    yield foobar(function () {
        return 'foo';
    });
    yield foobar(fn () => 'bar');
    yield foobar(function () {
        return 'baz';
    });
});

function baz(): bool
{
    return false;
}

it('works', function () {
    expect(true)->toBeTrue();
});