---
name: utility-classes
description: Use utility classes (Constants, Config_Sanitizer, String_Formatter, Value_Resolver) from CodeSoup Metabox Schema package. Use when working with package internals, extending classes, sanitizing configuration, formatting strings, or resolving values.
license: MIT
metadata:
  author: code-soup
  version: "1.0"
  package: codesoup/metabox-schema
---

# Utility Classes

Utility classes and traits that power the CodeSoup Metabox Schema package. These can be used independently or when extending the package.

## When to Use

- Accessing package constants and defaults
- Sanitizing field configuration
- Formatting field names and strings
- Resolving callable and entity method values
- Extending package functionality
- Building custom field processors

## Constants

Centralized package constants for consistent configuration.

See `examples/constants-usage.php` for complete examples.

## Config_Sanitizer

Sanitizes field configuration arrays to prevent XSS and ensure valid values.

Sanitizes: field names, types, labels, form prefix, rows, wrapper tags, options, attributes.

## String_Formatter

String formatting utilities for field names and conversions.

Methods: `format_field_name()`, `to_id_format()`, `to_attribute_format()`.

## Value_Resolver Trait

Provides value resolution logic for callable and entity method values.

See `examples/value-resolver-usage.php` for complete usage example.

## Important Notes

- All utility classes are in `CodeSoup\MetaboxSchema` namespace
- Config_Sanitizer uses Constants for validation
- String_Formatter methods are static
- Value_Resolver is a trait, not a class
- All sanitization happens at construction/validation time
- All escaping happens at output/render time

## See Also

- [Schema Field Renderer](../schema-field-renderer/SKILL.md) - Uses Value_Resolver and Config_Sanitizer
- [Schema Validator](../schema-validator/SKILL.md) - Uses Value_Resolver and String_Formatter
- [Schema Definition](../schema-definition/SKILL.md) - Field configuration structure

