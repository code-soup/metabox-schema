# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.0.2] - 2026-02-02

### Changed
- **Breaking**: Changed all `private` methods to `protected` in `Validator` class to enable extensibility
- **Breaking**: Changed all `private` methods to `protected` in `Field` class to enable extensibility
- **Breaking**: Refactored `Renderer` class to support extensibility with protected instance methods

### Added
- Added `protected renderFields()` method to `Renderer` class
- Added `protected renderField()` method to `Renderer` class
- Added `protected createField()` method to `Renderer` class
- Added `protected openGrid()` method to `Renderer` class
- Added `protected closeGrid()` method to `Renderer` class
- Added `protected handleRenderError()` method to `Renderer` class
- Added `examples/extend-validator.php` demonstrating custom validation rules
- Added `examples/extend-renderer.php` demonstrating custom rendering with Bootstrap integration
- Added "Extending Classes" section to README.md with documentation and examples

### Fixed
- Fixed `Validator::isEmptyValue()` incorrectly treating `0` and `'0'` as empty values
- Fixed `sanitizeByType()` using `absint()` which did not handle negative numbers or decimals correctly

## [0.0.1] - 2026-02-01

### Added
- Initial release
- Core `Validator` class for schema-based validation and sanitization
- Core `Field` class for field rendering
- Core `Renderer` class for form rendering
- Template system with overridable templates
- Support for text, email, url, number, textarea, and select field types
- Validation rules: required, min, max, pattern, options, format, custom callbacks
- Sanitization by type with callback support
- WordPress integration examples
- Custom template examples
- Basic usage examples
- Comprehensive README documentation

