<?php

it('fails when functions are not grouped together', function (string $filePath, array $linesWithErrors) {
    checkFile($filePath)->assertHasErrors($linesWithErrors);
})->with([
    'tests' => [
        __DIR__ . '/../../Stubs/Formatting/EnsureFunctionsAreGroupedSniff/tests_not_grouped_together.php',
        [7, 17],
    ],
    'datasets' => [
        __DIR__ . '/../../Stubs/Formatting/EnsureFunctionsAreGroupedSniff/datasets_not_grouped_together.php',
        [7, 13],
    ],
]);

it('can fix grouping functions together', function (string $original, string $fixed) {
    $this->fixFile($original)->assertMatchesFile($fixed);
})->with([
    'tests' => [
        __DIR__ . '/../../Stubs/Formatting/EnsureFunctionsAreGroupedSniff/tests_not_grouped_together.php',
        __DIR__ . '/../../Stubs/Formatting/EnsureFunctionsAreGroupedSniff/Fixed/tests_not_grouped_together.php',
    ],
    'datasets' => [
        __DIR__ . '/../../Stubs/Formatting/EnsureFunctionsAreGroupedSniff/datasets_not_grouped_together.php',
        __DIR__ . '/../../Stubs/Formatting/EnsureFunctionsAreGroupedSniff/Fixed/datasets_not_grouped_together.php',
    ],
    'tests and datasets' => [
        __DIR__ . '/../../Stubs/Formatting/EnsureFunctionsAreGroupedSniff/tests_and_datasets_not_grouped_together.php',
        __DIR__ . '/../../Stubs/Formatting/EnsureFunctionsAreGroupedSniff/Fixed/tests_and_datasets_not_grouped_together.php',
    ],
]);