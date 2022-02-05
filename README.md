# cake-dto-mapper

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.1-blue.svg?no-cache=1)](https://php.net/)

## Installation

    composer require bowlofsoup/cake-dto-mapper

## Quick overview

Has two main functionalities:

(1) Converts a CakePHP entity into a DTO; `EntityToDtoMapper`.

Since CakePHP, by default, does not have actual properties on an entity,
an interface must be implemented on the entity to ensure that `$this->_properties` is used.

(2) Converts an associative array into a DTO; `ArrayToDtoMapper`.