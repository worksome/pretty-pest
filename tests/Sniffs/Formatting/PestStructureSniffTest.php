<?php

it('requires new lines between test functions', function () {
    $this->checkFile(__DIR__ . '/../../Stubs/Formatting/PestStructureSniff/no_spaces_between_tests.php')
        ->assertHasErrors([5, 8, 15]);
});

it('can fix spacing correctly', function () {
    $this->fixFile(__DIR__ . '/../../Stubs/Formatting/PestStructureSniff/no_spaces_between_tests.php')
        ->assertMatchesFile(__DIR__ . '/../../Stubs/Formatting/PestStructureSniff/Fixed/no_spaces_between_tests.php');
});