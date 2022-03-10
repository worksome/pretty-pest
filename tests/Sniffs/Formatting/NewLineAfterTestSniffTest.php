<?php

it('requires new lines between test functions', function () {
    $this->checkFile(__DIR__ . '/../../Stubs/Formatting/NewLineAfterTestSniff/no_spaces_between_tests.php')
        ->assertHasErrors([5, 8]);
});

it('can fix missing new lines correctly', function (string $filePath, string $fixedFilePath) {
    $this->fixFile($filePath)->assertMatchesFile($fixedFilePath);
})->with([
//    [
//        __DIR__ . '/../../Stubs/Formatting/NewLineAfterTestSniff/no_spaces_between_tests.php',
//        __DIR__ . '/../../Stubs/Formatting/NewLineAfterTestSniff/Fixed/no_spaces_between_tests.php'
//    ],
    [
        __DIR__ . '/../../Stubs/Formatting/NewLineAfterTestSniff/file_where_first_function_is_nested.php',
        __DIR__ . '/../../Stubs/Formatting/NewLineAfterTestSniff/Fixed/file_where_first_function_is_nested.php'
    ]
]);