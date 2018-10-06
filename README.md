PHP XML Validator
=================

[![Build Status](https://travis-ci.com/libero/xml-validator.svg?branch=master)](https://travis-ci.com/libero/xml-validator)

This is a library for validating XML against one or more schemas.

Getting started
---------------

Using [Composer](https://getcomposer.org/) you can install the coding standard into your project:

```
composer require libero/xml-validator
```

The core of this library is the [`XmlValidator`](src/XmlValidator.php) interface, which can be used to test the validity of a [`DOMDocument`](https://php.net/DOMDocument).

### Implementations

#### [`ChainedValidator`](src/ChainedValidator.php)

Runs multiple validators and combines their results.

#### [`DummyValidator`](src/DummyValidator.php)

Always produces the configured result. Useful for testing.

Getting help
------------

-  Report a bug or request a feature on [GitHub](https://github.com/libero/libero/issues/new/choose).
-  Ask a question on the [Libero Community Slack](https://libero-community.slack.com/).
