# Hexagonal architecture playground: Payment package

## What is it?
This is a package that is part of the Hexagonal Architecture Playground project. Includes all services related to payment.

## Documentation

### Requirements
- docker

### Install
`composer require konair/hap-payment-package`

### Test with docker
1. `composer docker-build`
2. `composer docker`
3. `composer test`

### Domain parts of the package
- Cart
- Price
- Billing
- Payment
- Item access

### More information
- see in the [composer.json](composer.json) file
- see in the [Dockerfile](Dockerfile) file

## License
[GPLv3](LICENSE)
