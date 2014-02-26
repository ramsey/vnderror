# Rhumsaa\VndError for PHP

[![Build Status](https://secure.travis-ci.org/ramsey/vnderror.png)](http://travis-ci.org/ramsey/vnderror)
[![Latest Stable Version](https://poser.pugx.org/rhumsaa/vnderror/v/stable.png)](https://packagist.org/packages/rhumsaa/vnderror)
[![Latest Unstable Version](https://poser.pugx.org/rhumsaa/vnderror/v/unstable.png)](https://packagist.org/packages/rhumsaa/vnderror)
[![Total Downloads](https://poser.pugx.org/rhumsaa/vnderror/downloads.png)](https://packagist.org/packages/rhumsaa/vnderror)

Rhumsaa\VndError is a PHP implementation of the [vnd.error][] specification.
This implementation currently targets the [e88d5cd1ad][] revision of the
specification.

From the vnd.error specification:

> vnd.error is a simple way of expressing an error response in XML or JSON.
>
> Often when returning a response to a client a response type is needed to
> represent a problem to the user (human or otherwise). A media type
> representation is a convenient way of expressing the error in a standardised
> format and can be understood by many client applications.

## Examples

Use `application/vnd.error+json` or `application/vnd.error+xml` media types
to communicate errors with HTTP 4xx and 5xx status codes.

```php
use Rhumsaa\VndError\VndError;

$vndError = new VndError('Validation failed', 42);
$vndError->addLink('help', 'http://.../', array('title' => 'Error Information'));
$vndError->addLink('describes', 'http://.../', array('title' => 'Error Description'));
```

### JSON output (application/vnd.error+json):

```php
header('Content-Type: application/vnd.error+json');
echo $vndError->asJson();
```

Results in:

```json
{
    "message": "Validation failed",
    "logref": 42,
    "_links": {
        "help": {
            "href": "http://.../",
            "title": "Error Information"
        },
        "describes": {
            "href": "http://.../",
            "title": "Error Description"
        }
    }
}
```

### XML output (application/vnd.error+xml)

```php
header('Content-Type: application/vnd.error+xml');
echo $vndError->asXml();
```

Results in:

```xml
<?xml version="1.0"?>
<resource logref="42">
    <link rel="help" href="http://.../" title="Error Information"/>
    <link rel="describes" href="http://.../" title="Error Description"/>
    <message>Validation failed</message>
</resource>
```

## Installation

The preferred method of installation is via [Composer][]:

```bash
composer.phar require "rhumsaa/vnderror=~2.0"
```


[vnd.error]: https://github.com/blongden/vnd.error
[e88d5cd1ad]: https://github.com/blongden/vnd.error/blob/e88d5cd1ad467b653573471f0c859428bddaece8/README.md
[composer]: https://getcomposer.org/
