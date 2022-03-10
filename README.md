# Pretty Pest

Enforce consistent styling for your Pest PHP tests!

[![pest](https://github.com/worksome/pretty-pest/actions/workflows/pest.yml/badge.svg)](https://github.com/worksome/pretty-pest/actions/workflows/pest.yml)

There are lots of important decisions to worry about as a developer. You know what you shouldn't be worrying about?
The order of your tests! But you still want your tests to look nice, right?

That's why we created Pretty Pest. This code style plugin ensures that your Pest tests are automatically formatted correctly so that
you don't have to spend time doing so manually.

## Installation

You can install the package via composer.

```bash
composer require worksome/pretty-pest --dev
```

## PHPCBF

You can enable Pretty Pest in [PHPCBF](https://github.com/squizlabs/PHP_CodeSniffer) by adding the `PrettyPest` rule to your `phpcs.xml` file.

```xml
<rule ref="PrettyPest"/>
```

Pretty Pest has 2 sniffs for [PHPCBF](https://github.com/squizlabs/PHP_CodeSniffer):

- `EnsureFunctionsAreOrdered`
- `NewLineAfterTestSniff`

### EnsureFunctionsAreOrdered

This sniff will ensure that your pest functions are ordered correctly in the test file. By default, we use the following order:

```php
[
    'uses',
    'beforeAll',
    'beforeEach',
    'afterEach',
    'afterAll',
    ['test', 'it'],
    'dataset',
]
```

Any functions that are out of order will be moved in the file. Of course, you can always override this order to your preference in the `phpcs.xml` file.

```xml
<rule ref="PrettyPest.Formatting.EnsureFunctionsAreOrdered">
    <properties>
        <property name="order" type="array">
            <element value="uses"/>
            <element value="beforeAll"/>
            <element value="beforeEach"/>
            <element value="test"/>
            <element value="it"/>
            <element value="dataset"/>
            <element value="afterEach"/>
            <element value="afterAll"/>
        </property>
    </properties>
</rule>
```

### NewLineAfterTest

This sniff will replace all whitespace after a Pest function with a single new line to ensure that spacing in your test files is consistent.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Luke Downing](https://github.com/lukeraymonddowning)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.