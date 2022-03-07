<?php

it('fails when functions are not sorted', function (string $filePath, array $linesWithErrors) {
    checkFile($filePath)->assertHasErrors($linesWithErrors);
})->with([
    'uses not first' => [
        __DIR__ . '/../../Stubs/Formatting/EnsureFunctionsAreOrderedSniff/uses_not_first.php',
        [14],
    ],
    'dataset out of order' => [
        __DIR__ . '/../../Stubs/Formatting/EnsureFunctionsAreOrderedSniff/dataset_out_of_order.php',
        [7, 9, 13],
    ],
]);

it('can sort the function groups', function (string $filePath, string $fixedFile) {
    fixFile($filePath)->assertMatchesFile($fixedFile);
})->with([
    'uses not first' => [
        __DIR__ . '/../../Stubs/Formatting/EnsureFunctionsAreOrderedSniff/uses_not_first.php',
        __DIR__ . '/../../Stubs/Formatting/EnsureFunctionsAreOrderedSniff/Fixed/uses_not_first.php',
    ],
    'dataset out of order' => [
        __DIR__ . '/../../Stubs/Formatting/EnsureFunctionsAreOrderedSniff/dataset_out_of_order.php',
        __DIR__ . '/../../Stubs/Formatting/EnsureFunctionsAreOrderedSniff/Fixed/dataset_out_of_order.php',
    ],
]);