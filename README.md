# cake-dto-mapper

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.1-blue.svg?no-cache=1)](https://php.net/)

## Installation

    composer require bowlofsoup/cake-dto-mapper

## Quick overview

Converts a CakePHP entity into a DTO.

Since CakePHP, by default, does not have actual properties on an entity,
an interface must be implemented on the entity to ensure that `$this->_properties` is used.