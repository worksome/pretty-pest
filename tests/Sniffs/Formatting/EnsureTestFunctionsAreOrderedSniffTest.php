<?php

it('creates an error on the first line when functions are not sorted', function (string $filePath, array $linesWithErrors) {
    checkFile($filePath)->assertHasErrors($linesWithErrors);
})->with([
    'uses not first' => [
        __DIR__ . '/../../Stubs/Formatting/EnsureTestFunctionsAreOrderedSniff/uses_not_first.php',
        [1],
    ],
    'dataset out of order' => [
        __DIR__ . '/../../Stubs/Formatting/EnsureTestFunctionsAreOrderedSniff/dataset_out_of_order.php',
        [1],
    ],
]);

it('can sort the function groups', function (string $filePath, string $fixedFile) {
    fixFile($filePath)->assertMatchesFile($fixedFile);
})->with([
    'uses not first' => [
        __DIR__ . '/../../Stubs/Formatting/EnsureTestFunctionsAreOrderedSniff/uses_not_first.php',
        __DIR__ . '/../../Stubs/Formatting/EnsureTestFunctionsAreOrderedSniff/Fixed/uses_not_first.php',
    ],
    'dataset out of order' => [
        __DIR__ . '/../../Stubs/Formatting/EnsureTestFunctionsAreOrderedSniff/dataset_out_of_order.php',
        __DIR__ . '/../../Stubs/Formatting/EnsureTestFunctionsAreOrderedSniff/Fixed/dataset_out_of_order.php',
    ],
    'dataset with yields' => [
        __DIR__ . '/../../Stubs/Formatting/EnsureTestFunctionsAreOrderedSniff/file_where_first_function_is_nested.php',
        __DIR__ . '/../../Stubs/Formatting/EnsureTestFunctionsAreOrderedSniff/Fixed/file_where_first_function_is_nested.php',
    ]
]);

it('does not create an error if the order is as expected', function () {
    checkFile(__DIR__ . '/../../Stubs/Formatting/EnsureTestFunctionsAreOrderedSniff/Fixed/dataset_out_of_order.php')
        ->assertHasNoErrors();
});

it('can provide a custom sort order', function () {
    fixFile(
        __DIR__ . '/../../Stubs/Formatting/EnsureTestFunctionsAreOrderedSniff/dataset_out_of_order.php',
        [
            'order' => [
                ['uses'],
                ['dataset'],
                ['test', 'it'],
            ],
        ]
    )->assertMatchesFile(__DIR__ . '/../../Stubs/Formatting/EnsureTestFunctionsAreOrderedSniff/Fixed/dataset_out_of_order_with_custom_sort_order.php');
});

it('does not crash', function () {
    checkFile(__DIR__ . '/../../Stubs/Formatting/EnsureTestFunctionsAreOrderedSniff/discovered_recursive_error.php')
        ->assertHasNoErrors();
});