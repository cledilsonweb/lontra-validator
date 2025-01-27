# Changelog

All notable changes to `lontra-validator` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

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
