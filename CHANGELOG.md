# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2026-XX-XX

### BREAKING CHANGES

#### Custom Field Registration - Instance-Based Only

Custom field types must now be registered on Renderer instances instead of globally via Field_Factory. This prevents plugin conflicts in multi-plugin WordPress environments.

**Migration Required:**

Before (v1.0.x):
```php
Field_Factory::register_field_type( 'color_picker', Color_Picker_Field::class );
Renderer::render( $config );
```

After (v1.1.0):
```php
$renderer = new Renderer();
$renderer->register_field_type( 'color_picker', Color_Picker_Field::class );
$renderer->render_fields( $config );
```

**Why:** Static registration created shared global state. Multiple plugins registering the same field type would overwrite each other, causing unpredictable behavior and potential security issues.

See `skills/custom-field-registration/examples/multi-plugin-isolation.md` for detailed migration guide.

### Added

- **Renderer::register_field_type()** - Instance-based custom field registration with validation
- **Renderer::create_custom_field()** - Helper method for instantiating custom fields
- **Media_Field::get_asset_url()** - Auto-detects package location (plugin/theme/vendor)
- **Media_Field::get_version()** - Reads version from composer.json or METABOX_SCHEMA_VERSION constant
- **HTML_Field::get_allowed_html()** - Configurable HTML tag allowlist for wp_kses
- WP_DEBUG warning when grid not explicitly closed in schema
- Static singleton pattern for Config_Sanitizer (performance: 1 instance instead of N)
- Multi-plugin isolation example documentation

### Changed

- **Renderer::render_fields()** - Changed from `protected` to `public` (allows direct instance usage)
- **Abstract_Field::__construct()** - Added entity type validation (must be object or null)
- **Abstract_Field::generate_field_id()** - Forces dashes everywhere (both prefix and name)
- **Abstract_Field::sanitize_config()** - Uses shared static sanitizer instance
- **Config_Sanitizer::sanitize()** - Removed unnecessary `is_callable()` checks
- **Validator::validate_value()** - Renamed to `has_validation_errors()` with `?string` return type
- **Validator::is_empty_value()** - Now treats boolean `false` as empty
- **Validator::sanitize_by_type()** - Preserves integer type for whole numbers
- **Value_Resolver::execute_with_error_handling()** - Returns `$fallback` instead of `$callback` on error
- **Abstract_Field::resolve_value()** - Uses `array_key_exists()` to detect explicit `null`
- **HTML template** - Uses custom safe allowlist instead of 'post' context
- **Media template** - Uses CSS `.hidden` class instead of inline styles
- **Field_Factory::create()** - Added type assertion for static analysis tools

### Security

- **Wrapper tags** - Added `esc_html()` escaping
- **Select options** - Added `esc_attr()` for values, `esc_html()` for labels
- **Attributes** - Values sanitized with `sanitize_text_field()`
- **Inline styles** - Replaced with CSS classes

### Fixed

- **Type Safety:** Entity validation in Abstract_Field constructor (must be object or null)
- **Type Safety:** Validator return type changed from `string|bool` to `?string` for clarity
- **Type Safety:** Added assert() in Field_Factory::create() for static analysis tools
- **Validation:** Field_Factory type must be string (prevents array/object injection)
- **Validation:** Renderer schema structure validated (field names must be strings, configs must be arrays)
- **Validation:** Abstract_Field form_prefix format validated (alphanumeric, hyphens, underscores only)
- **Validation:** Validator min/max must be int or float (prevents array/object injection)
- Field_Factory::is_supported() now correctly checks custom types (instance registry)
- Number sanitization preserves integer type when value has no decimals
- Null value resolution distinguishes explicit `null` from missing config key
- Callable error fallback no longer returns the callable object itself
- Grid auto-close warns in WP_DEBUG mode instead of silently closing

### Removed

- **Field_Factory::register_field_type()** - Removed static registration (BREAKING)
- **Field_Factory::$custom_field_types** - Removed static property (BREAKING)
- Static custom field type support from Field_Factory::create()
- Static custom field type check from Field_Factory::is_supported()

### Performance

- Config_Sanitizer: 1 shared instance instead of N instances per render
- Removed 250 unnecessary `is_callable()` checks per 50-field form
- Optimized field ID generation with clearer logic
- Renderer: Buffer entire form once instead of per-field (100 fields = 1 buffer vs 100)
- Abstract_Field: Direct concatenation for attributes instead of sprintf (500 fewer calls per 100-field form)

### Documentation

- Updated README.md with instance-based registration examples
- Updated skills/custom-field-registration/SKILL.md
- Added multi-plugin-isolation.md example showing conflict prevention
- Updated all custom field registration examples
- Removed all references to deprecated static methods

## [1.0.0] - 2026-03-14

### Added

- Field_Factory class for creating field instances
- Abstract_Field base class for all field types
- Dedicated field classes: Input_Field, Textarea_Field, Select_Field, Media_Field, WP_Editor_Field, HTML_Field, Label_Field, Help_Field
- Media field with WordPress media library integration
- HTML field for custom content display
- Label and Help field types
- Constants: DEFAULT_GRID_CLASS, DEFAULT_DATE_FORMAT, RESERVED_ATTRIBUTES
- Media field assets (CSS/JS) in field subdirectories
- Template structure with subdirectories (input/template.php, etc.)

### Changed

- PHP 8.1+ required
- Template structure uses subdirectories
- Field assets organized in field-specific folders
- Method names follow WordPress snake_case convention
- Grid class now configurable via get_grid_class() method

### Fixed

- XSS vulnerability in field attributes
- DRY violations and code duplication
- Error handling in Value_Resolver trait
- Float comparison precision issues
- WPCS violations

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
- Added `docs/extend-validator.php` demonstrating custom validation rules
- Added `docs/extend-renderer.php` demonstrating custom rendering with Bootstrap integration
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
