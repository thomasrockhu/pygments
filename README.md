<h1 align="center">ramsey/pygments</h1>

<p align="center">
    <strong>A PHP wrapper for Pygments, the Python syntax highlighter.</strong>
</p>

<p align="center">
    <a href="https://github.com/ramsey/pygments"><img src="http://img.shields.io/badge/source-ramsey/pygments-blue.svg?style=flat-square" alt="Source Code"></a>
    <a href="https://packagist.org/packages/ramsey/pygments"><img src="https://img.shields.io/packagist/v/ramsey/pygments.svg?style=flat-square&label=release" alt="Download Package"></a>
    <a href="https://php.net"><img src="https://img.shields.io/packagist/php-v/ramsey/pygments.svg?style=flat-square&colorB=%238892BF" alt="PHP Programming Language"></a>
    <a href="https://github.com/ramsey/pygments/actions?query=workflow%3ACI"><img src="https://img.shields.io/github/workflow/status/ramsey/pygments/CI?label=CI&logo=github&style=flat-square" alt="Build Status"></a>
    <a href="https://codecov.io/gh/ramsey/pygments"><img src="https://img.shields.io/codecov/c/gh/ramsey/pygments?label=codecov&logo=codecov&style=flat-square" alt="Codecov Code Coverage"></a>
    <a href="https://shepherd.dev/github/ramsey/pygments"><img src="https://img.shields.io/endpoint?style=flat-square&url=https%3A%2F%2Fshepherd.dev%2Fgithub%2Framsey%2Fpygments%2Fcoverage" alt="Psalm Type Coverage"></a>
    <a href="https://github.com/ramsey/pygments/blob/master/LICENSE"><img src="https://img.shields.io/packagist/l/ramsey/pygments.svg?style=flat-square&colorB=darkcyan" alt="Read License"></a>
    <a href="https://packagist.org/packages/ramsey/pygments/stats"><img src="https://img.shields.io/packagist/dt/ramsey/pygments.svg?style=flat-square&colorB=darkmagenta" alt="Package downloads on Packagist"></a>
    <a href="https://phpc.chat/channel/ramsey"><img src="https://img.shields.io/badge/phpc.chat-%23ramsey-darkslateblue?style=flat-square" alt="Chat with the maintainers"></a>
</p>

## About

ramsey/pygments is a PHP wrapper for [Pygments](https://pygments.org), the
Python syntax highlighter, forked from the
[Pygments.php](https://github.com/kzykhys/Pygments.php) project.

This project adheres to a [code of conduct](CODE_OF_CONDUCT.md).
By participating in this project and its community, you are expected to
uphold this code.

## Installation

Install this package as a dependency using [Composer](https://getcomposer.org).

``` bash
composer require ramsey/pygments
```

### Requirements

* PHP 7.3 or greater (including PHP 8)
* Python
* Pygments (`pip install Pygments`)

Python and Pygments versions supported:

| Pygments:  | 1.6 | 2.0 | 2.1 | 2.2 | 2.3 | 2.4 | 2.5 | 2.6 | 2.7 | 2.8 |
| :--------- | :-: | :-: | :-: | :-: | :-: | :-: | :-: | :-: | :-: | :-: |
| Python 3.6 | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   |
| Python 3.7 | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   |
| Python 3.8 | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   |
| Python 3.9 | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   |

## Usage

### Highlight source code

``` php
use Ramsey\Pygments\Pygments;

$pygments = new Pygments();
$html = $pygments->highlight(file_get_contents('index.php'), 'php', 'html');
$console = $pygments->highlight('package main', 'go', 'ansi');
```

### Generate CSS

``` php
use Ramsey\Pygments\Pygments;

$pygments = new Pygments();
$css = $pygments->getCss('monokai');
$prefixedCss = $pygments->getCss('default', '.syntax');
```

### Guess lexer name

``` php
use Ramsey\Pygments\Pygments;

$pygments = new Pygments();
$pygments->guessLexer('foo.rb'); // ruby
```

### Get a list of lexers/formatters/styles

``` php
use Ramsey\Pygments\Pygments;

$pygments = new Pygments();
$pygments->getLexers()
$pygments->getFormatters();
$pygments->getStyles();
```

### Set a custom `pygmentize` path

``` php
use Ramsey\Pygments\Pygments;

$pygments = new Pygments('/path/to/pygmentize');
```

## Contributing

Contributions are welcome! To contribute, please familiarize yourself with
[CONTRIBUTING.md](CONTRIBUTING.md).

## Copyright and License

Copyrights for portions of ramsey/pygments are held by
[Kazuyuki Hayashi](https://github.com/kzykhys) as part of
[Pygments.php](https://github.com/kzykhys/Pygments.php). All other copyrights
for ramsey/pygments are held by [Ben Ramsey](https://benramsey.com).

The ramsey/pygments library is licensed for use under the terms of the
MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
