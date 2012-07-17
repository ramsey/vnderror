# Rhumsaa\VndError

[![Build Status](https://secure.travis-ci.org/ramsey/vnderror.png)](http://travis-ci.org/ramsey/vnderror)

Rhumsaa\VndError is a PHP implementation of the [vnd.error][] specification.
This implementation currently targets the [1b9174148c][] revision of the
specification.

From the vnd.error specification:

> vnd.error is a simple way of expressing an error response in XML or JSON.
>
> Often when returning a response to a client a response type is needed to
> represent a problem to the user (human or otherwise). A media type
> representing this error is a convenient way of expressing the error in a
> standardised format and can be understood by many client applications.
>
> This media type is intended for use with the HTTP status codes 4xx and 5xx,
> though this does not exclude it from use in any other scenario.

## Examples

Use `application/vnd.error+json` or `application/vnd.error+xml` media types
to communicate errors with HTTP 4xx and 5xx status codes.

```php
<?php
$vndError = new Rhumsaa\VndError\VndError();
$error = $vndError->addError('id-1234', 'Invalid title in POST body');
$error->addLink('help', 'http://example.org/api-docs/title', 'The title field');

// For application/vnd.error+json
header('Content-Type: application/vnd.error+json');
echo $vndError->asJson();

// For application/vnd.error+xml
header('Content-Type: application/vnd.error+xml');
echo $vndError->asXml();
```

## Installation

The preferred method of installation is via [Packagist][], as this provides
the PSR-0 autoloader functionality. The following `composer.json` will download
and install the latest version of the VndError library into your project:

```json
{
    "require": {
        "rhumsaa/vnderror": "*"
    }
}
```


[vnd.error]: https://github.com/blongden/vnd.error
[1b9174148c]: https://github.com/blongden/vnd.error/blob/1b9174148ca3164e0bc8888eef46def7527c3db1/README.md
[packagist]: http://packagist.org/
