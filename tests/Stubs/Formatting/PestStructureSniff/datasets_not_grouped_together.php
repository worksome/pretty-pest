<?php

var_dump('here');

dataset('foo', ['foo', 'baz', 'bar']);

var_dump('here');

var_dump('here');

dataset('names', ['luke', 'oliver', 'jeremy']);

var_dump('here');

dataset('hello', function () {
    return ['hello', 'world'];
});