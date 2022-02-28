<?php

it('fails when tests are not grouped together', function () {
    $this->checkFile(__DIR__ . '/../../Stubs/Formatting/PestStructureSniff/tests_not_grouped_together.php')
        ->assertHasErrors([9, 19]);
});