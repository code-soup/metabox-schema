# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.0.4] - 2026-02-28

### Added

- Constants class - centralized package constants
- Config_Sanitizer class - configuration sanitization
- String_Formatter class - string formatting utilities
- Value_Resolver trait - shared value resolution logic
- Terminology section in README
- Advanced Usage section in README
- Package Architecture section in README
- utility-classes Agent Skill

### Changed

- Field class now uses Value_Resolver trait and Config_Sanitizer
- Validator class now uses Value_Resolver trait and String_Formatter
- All utility class names use WordPress underscore convention
- Updated all documentation with new architecture
- Enhanced Requirements section with WordPress functions list
- Improved Extending Classes section with utility class examples

### Fixed

- Removed dead code in Validator::resolve_default()
- Removed duplication between Field and Validator classes
- Date validation now uses strict DateTime::createFromFormat()
- URL validation now uses wp_http_validate_url() for consistency
- Reserved keyword warning for $default parameter
- Field constructor now uses array_key_exists() instead of isset() to allow null entity values
- Field class now references Constants:: instead of self:: for package constants

## [0.0.3] - 2026-02-07

### Changed

- Improved `Field::resolveValue()` method with better type safety and callable handling
- Refactored value resolution logic to handle callables, entity methods, and static values more reliably

### Added

- Added comprehensive "Field Values" section to README explaining four value resolution approaches
- Added "How Value Resolution Works" section to README with detailed examples
- Added `value` property examples to all example files
- Added inline comments to `Field::resolveValue()` method for clarity
- Added entity wrapper examples in WordPress metabox implementation

### Fixed

- Fixed type safety issue in `Field::resolveValue()` where `trim()` was called on non-string values
- Fixed null-safe entity access in `Field::resolveValue()` to prevent notices
- Fixed callable detection to properly handle closures, array callables, and invokable objects

## [0.0.2] - 2026-02-07

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
