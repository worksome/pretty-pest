<?php

it('requires new lines between test functions', function () {
    $this->checkFile(__DIR__ . '/../../Stubs/Formatting/NewLineAfterTestSniff/no_spaces_between_tests.php')
        ->assertHasErrors([5, 8, 16, 17]);
});

it('can fix missing new lines correctly', function () {
    $this->fixFile(__DIR__ . '/../../Stubs/Formatting/NewLineAfterTestSniff/no_spaces_between_tests.php')
        ->assertMatchesFile(__DIR__ . '/../../Stubs/Formatting/NewLineAfterTestSniff/Fixed/no_spaces_between_tests.php');
});