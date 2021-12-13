# Templates

[![swagger](https://img.shields.io/badge/documentation-swagger-green)](https://escolalms.github.io/Templates/)
[![codecov](https://codecov.io/gh/EscolaLMS/Templates/branch/main/graph/badge.svg?token=NRAN4R8AGZ)](https://codecov.io/gh/EscolaLMS/Templates)
[![phpunit](https://github.com/EscolaLMS/Templates/actions/workflows/test.yml/badge.svg)](https://github.com/EscolaLMS/Templates/actions/workflows/test.yml)
[![downloads](https://img.shields.io/packagist/dt/escolalms/templates)](https://packagist.org/packages/escolalms/templates)
[![downloads](https://img.shields.io/packagist/v/escolalms/templates)](https://packagist.org/packages/escolalms/templates)
[![downloads](https://img.shields.io/packagist/l/escolalms/templates)](https://packagist.org/packages/escolalms/templates)

General purpose of this package is to store various templates in database and assigning them to Events so that content based on these templates is automatically generated and/or sent to users.

Each template is defined by

- `channel`: class defining how the template is handled
- `event`: event to which the template is assigned

For every channel & event pair a single Variables definition is registered, which contains tokens that can be used in the template and replaced with values based on the data from the Event.

Analysing these three example files in [tests](tests/Mock):

- TestChannel.php
- TestVariables.php
- TestEventWithGetters.php

and looking at the `Template` facade is simplest way to understand how this package works.

## Facade

There is a `Template` facade declared, which is used to register Event-Channel-Variable sets and can be used in testing (as it can be replaced with a fake using `Template::fake()`).

To register Event-Channel-Variable set, `Template::register($eventClass, $channelClass, $variableClass)` must be called, where:

- `$eventClass` can be any class that is dispatched as an event in any EscolaLms package
- `$channelClass` must be a class implementing `TemplateChannelContract` interface declared in this package
- `$variableClass` must be a class implementing `TemplateVariableContract` interface declared in this package

## Channels & variables

This package has no Channels or Variables defined, everything should be created in separate packages.

- [Templates-Email](https://github.com/EscolaLMS/Templates-Email)
- [Templates-Certificates](https://github.com/EscolaLMS/Templates-Certificates)
