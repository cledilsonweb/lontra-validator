# Changelog

All notable changes to `lontra-validator` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

Versão 0.3.0
## [0.3.0] - 2025-03-19

### Added
- Lontra\Validator\Validator: Instancia uma classe de validação de Laminas Validator e Lontra Validator e valida em uma única chamada.
- ValidatorTest: Validação da nova classe Validator
- Exemplos de personalização de mensagens das validações e também do uso de Valitador no README

### Fix
- DateGreaterThanTest: Nome do arquivo PHP inválido.
- DateLessThanTest: Nome do arquivo PHP inválido.

## [0.2.2] - 2023-10-10

### Fix
- Lontra\Validator\DateGreaterThan: const INVALID_* para substituir const IVALID_* que estão depreciados nesta versão.
- Lontra\Validator\DateGreaterThan: Mensagem "The input is not greater than(inclusive)" alterada para "The input is not greater than or equal to"

### Added
- TogetherValidator: Valida se os campos foram preenchidos em conjunto

## [0.0.2] - 2020-07-24

### Added
- DateBetween Validator
- DateGreaterThan Validator
- DateLessThan Validator
- Password Validator
- InvalidDateException
- EndWith
- StartWith
- WordCount
