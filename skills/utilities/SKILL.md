---
name: utilities
description: Use utility classes for constants, sanitization, string formatting, and value resolution. Use when accessing package constants, sanitizing field configuration, formatting field names, or resolving callable and entity method values.
license: MIT
location: includes/utilities/
metadata:
    author: code-soup
    version: "1.0"
    package: codesoup/metabox-schema
---

# Utility Classes

Utility classes and traits for constants, sanitization, string formatting, and value resolution. Can be used independently or when extending field functionality.

## When to Use

- Accessing package constants and defaults
- Sanitizing field configuration
- Formatting field names and strings
- Resolving callable and entity method values
- Extending package functionality
- Building custom field processors

## Constants

Centralized package constants for consistent configuration.

See `examples/constants-usage.md` for complete examples.

## Config_Sanitizer

Sanitizes field configuration arrays to prevent XSS and ensure valid values.

Sanitizes: field names, types, labels, form prefix, rows, wrapper tags, options, attributes.

## String_Formatter

String formatting utilities for field names and conversions.

Methods: `format_field_name()`, `to_id_format()`, `to_attribute_format()`.

## Value_Resolver Trait

Provides value resolution logic for callable and entity method values.

See `examples/value-resolver-usage.md` for complete usage example.

## Debug_Helper Trait

Centralized debug output that respects WP_DEBUG setting.

Methods:
- `maybe_trigger_error( $message, $level )` - Triggers error only when WP_DEBUG enabled
- `maybe_output_debug_comment( $message )` - Outputs HTML comment only when WP_DEBUG enabled

Used internally by Renderer, Abstract_Field, and Value_Resolver.

## Important Notes

- All utility classes are in `CodeSoup\MetaboxSchema` namespace
- Config_Sanitizer uses Constants for validation
- Config_Sanitizer supports nested options (optgroups) for select fields
- String_Formatter methods are static
- Value_Resolver is a trait, not a class
- Debug_Helper is a trait for debug output
- All sanitization happens at construction/validation time
- All escaping happens at output/render time

## See Also

- [field-renderer](/skills/field-renderer/SKILL.md) - Uses Value_Resolver and Config_Sanitizer
- [validator](/skills/validator/SKILL.md) - Uses Value_Resolver and String_Formatter
- [schema-definition](/skills/schema-definition/SKILL.md) - Field configuration structure
