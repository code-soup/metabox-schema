# Wrapper Features Implementation Summary

## Overview

Three new features have been implemented to enhance field wrapper functionality:

1. **Custom CSS Classes on Wrapper** - Add custom CSS classes to field wrapper elements
2. **Automatic Required Class** - Automatically add CSS class when field is required
3. **Help Text Position** - Control whether help text appears before or after the field input

## Files Modified

### 1. Constants Class (`includes/utilities/class-constants.php`)

Added three new constants:

- `DEFAULT_REQUIRED_CLASS = 'required'` - Default CSS class for required fields
- `DEFAULT_HELP_POSITION = 'after'` - Default help text position
- `VALID_HELP_POSITIONS = ['before', 'after']` - Valid help position values

### 2. Config_Sanitizer (`includes/core/class-config-sanitizer.php`)

Added sanitization for new configuration options:

- `sanitize_wrapper_class()` - Handles both string and array formats, sanitizes with `sanitize_html_class()`
- `sanitize_help_position()` - Validates against `VALID_HELP_POSITIONS`, falls back to default
- Added `required_class` to `SANITIZATION_RULES` with `sanitize_html_class` callback

### 3. Abstract_Field (`includes/fields/class-abstract-field.php`)

#### New Methods

- `get_wrapper_id()` - Returns field ID + '-wrap' suffix
- `get_wrapper_class()` - Combines custom classes with automatic required class
- `get_required_class()` - Returns required class name (field override or default)
- `get_help_position()` - Returns help position ('before' or 'after')

#### Modified Methods

- `open_wrapper()` - Now outputs `id` and `class` attributes on wrapper element
- `render()` - Conditionally renders help text before or after field based on `help_position`

### 4. Renderer (`includes/core/class-renderer.php`)

Added support for global defaults:

- `default_required_class` - Global default for required class name
- `default_help_position` - Global default for help text position
- `apply_defaults_to_schema()` - Applies global defaults to fields that don't have explicit values

## Configuration Options

### Field-Level Configuration

```php
$schema = array(
    'field_name' => array(
        'wrapper_class'  => 'custom-class',        // String or array
        'required_class' => 'mandatory',           // Override required class
        'help_position'  => 'before',              // 'before' or 'after'
        // ... other field config
    ),
);
```

### Global Configuration

```php
Renderer::render(
    array(
        'schema'                 => $schema,
        'form_prefix'            => 'form',
        'default_required_class' => 'is-required',  // Applied to all required fields
        'default_help_position'  => 'before',       // Applied to all fields with help text
    )
);
```

## Feature Details

### 1. Custom Wrapper Classes

**Format Support:**
- String: `'wrapper_class' => 'class-one class-two'`
- Array: `'wrapper_class' => ['class-one', 'class-two']`

**Default Wrapper Class:**
- None (only auto-generated ID is always present)

**Wrapper ID:**
- Auto-generated: `{field-id}-wrap`
- Example: Field ID `form-email` → Wrapper ID `form-email-wrap`

### 2. Required Class

**Automatic Behavior:**
- When `validation.required = true`, wrapper automatically gets required class
- Default class name: `'has-required'`

**Override Options:**
- Field-level: `'required_class' => 'custom-name'`
- Global: `'default_required_class' => 'custom-name'`

**Priority:**
1. Field-level `required_class` (highest)
2. Global `default_required_class`
3. `Constants::DEFAULT_REQUIRED_CLASS` (fallback)

### 3. Help Text Position

**Positions:**
- `'before'` - Help text appears between label and input
- `'after'` - Help text appears after input (default)

**Override Options:**
- Field-level: `'help_position' => 'before'`
- Global: `'default_help_position' => 'before'`

**Priority:**
1. Field-level `help_position` (highest)
2. Global `default_help_position`
3. `Constants::DEFAULT_HELP_POSITION` (fallback)

**Render Order:**
- `'after'`: Label → Input → Help
- `'before'`: Label → Help → Input

## Examples

See `docs/wrapper-features-examples.md` for comprehensive usage examples.

## Backward Compatibility

All features are backward compatible:

- Existing schemas work without modification
- Default behavior unchanged (no wrapper classes, help after field)
- New configuration options are optional
- Sanitization prevents invalid values

## Testing

Manual testing recommended in WordPress environment:

1. Test custom wrapper classes (string and array formats)
2. Verify required class auto-applies on required fields
3. Test help position before and after
4. Verify global defaults work
5. Verify field-level overrides take precedence
6. Check wrapper ID generation

## CSS Styling Example

```css
/* Style required fields */
.has-required {
    border-left: 4px solid red;
}

/* Custom field styles */
.custom-field {
    padding: 15px;
    background: #f5f5f5;
}

/* Help text before field */
.help-before small {
    margin-bottom: 5px;
}
```
