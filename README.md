# Path converter

[![Build status](https://img.shields.io/github/workflow/status/matthiasmullie/path-converter/test-suite?style=flat-square)](https://github.com/matthiasmullie/path-converter/actions/workflows/test.yml)
[![Code coverage](http://img.shields.io/codecov/c/gh/matthiasmullie/path-converter?style=flat-square)](https://codecov.io/gh/matthiasmullie/path-converter)
[![Latest version](http://img.shields.io/packagist/v/matthiasmullie/path-converter?style=flat-square)](https://packagist.org/packages/matthiasmullie/path-converter)
[![Downloads total](http://img.shields.io/packagist/dt/matthiasmullie/path-converter?style=flat-square)](https://packagist.org/packages/matthiasmullie/path-converter)
[![License](http://img.shields.io/packagist/l/matthiasmullie/path-converter?style=flat-square)](https://github.com/matthiasmullie/path-converter/blob/master/LICENSE)


## Usage

```php
use MatthiasMullie\PathConverter\Converter;

$from = '/css/imports/icons.css';
$to = '/css/minified.css';

$converter = new Converter($from, $to);
$result = $converter->convert('../../images/icon.jpg');
// $result is now '../images/icon.jpg'
```


## Methods

### __construct($from, $to)

The object constructor accepts 2 paths: the source path your file(s) is/are
currently relative to, and the target path to convert to.

### convert($path): string

$path is the relative file, which is currently relative to $from (in
constructor). The return value will be the relative path of this same file, but
now relative to $to (in constructor)


## Installation

Simply add a dependency on `matthiasmullie/path-converter` to your composer.json file if you use [Composer](https://getcomposer.org/) to manage the dependencies of your project:

```sh
composer require matthiasmullie/path-converter
```

Although it's recommended to use Composer, you can actually include these files anyway you want.


## License

PathConverter is [MIT](http://opensource.org/licenses/MIT) licensed.
