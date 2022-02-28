<?php

it('fails when tests are not grouped together', function () {
    $this->checkFile(__DIR__ . '/../../Stubs/Formatting/PestStructureSniff/tests_not_grouped_together.php')
        ->assertHasErrors([9, 19]);
});

it('can fix grouping tests together', function () {
    $this->fixFile(__DIR__ . '/../../Stubs/Formatting/PestStructureSniff/tests_not_grouped_together.php')
        ->assertMatchesFile(__DIR__ . '/../../Stubs/Formatting/PestStructureSniff/Fixed/tests_not_grouped_together.php');
});