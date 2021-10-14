# Templates

[![swagger](https://img.shields.io/badge/documentation-swagger-green)](https://escolalms.github.io/Templates/)
[![codecov](https://codecov.io/gh/EscolaLMS/Templates/branch/main/graph/badge.svg?token=NRAN4R8AGZ)](https://codecov.io/gh/EscolaLMS/Templates)
[![phpunit](https://github.com/EscolaLMS/Templates/actions/workflows/test.yml/badge.svg)](https://github.com/EscolaLMS/Templates/actions/workflows/test.yml)
[![downloads](https://img.shields.io/packagist/dt/escolalms/templates)](https://packagist.org/packages/escolalms/templates)
[![downloads](https://img.shields.io/packagist/v/escolalms/templates)](https://packagist.org/packages/escolalms/templates)
[![downloads](https://img.shields.io/packagist/l/escolalms/templates)](https://packagist.org/packages/escolalms/templates)

General purpose of this package is to store in database various templates.

So far this is a straightforward implementation that use [strtr](https://www.php.net/manual/en/function.strtr.php).

Each template is defined by

- `type`: so far Email or PDF
- `vars_set`: which variable set it contains
- `validation`: eg. Confirmation email must contain Confirmation URL Link.

There is and example in [tests](tests/Enum) for overall analysis how does this package works.

## Variable Set

This package has no Variable Set. Each of this is defined be another package

- [Templates-Email](https://github.com/EscolaLMS/Templates-Email)
- [Templates-Certificates](https://github.com/EscolaLMS/Templates-Certificates)

## Papeteer to generate PDF

Best result generating PDF from HTML is by using headless chromium like papeteer.
Caveat is that is does require installing Node.js and many binaries. This package use [spatie/browsershot](https://github.com/spatie/browsershot).
Full list of installation requirements can be found on [their documentation](https://github.com/spatie/browsershot#requirements).
Our [Docker PHP images](https://hub.docker.com/r/escolalms/php) have this preinstalled already.

## Email Templates

By default Laravel store email templates in blade files which is fine in most cases. Yet in Escola LMS we want to give administrators
ability to manage content of the templates and store them in DB instead of `*.blade.php` files.
