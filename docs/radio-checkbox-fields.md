# Radio and Checkbox Fields

## Overview

The package now includes two new field types:

1. **Radio Field** - Single selection from multiple options
2. **Checkbox Field** - Single checkbox for yes/no or agreement scenarios

## Radio Field

### Description
Radio buttons allow users to select **one option** from multiple choices. Similar to a select dropdown but displays all options inline.

### Basic Usage

```php
$schema = array(
    'gender' => array(
        'type'    => 'radio',
        'label'   => 'Gender',
        'options' => array(
            'male'   => 'Male',
            'female' => 'Female',
            'other'  => 'Other',
        ),
        'value'   => 'male',
    ),
);
```

### Features

- **Single selection** - Only one option can be selected
- **Options array** - Same format as select fields
- **Validation** - Validates against available options
- **Required support** - Can be marked as required field

### Schema Options

| Option | Type | Description |
|--------|------|-------------|
| `type` | string | Must be `'radio'` |
| `label` | string | Field label text |
| `options` | array | Available options (key => label) |
| `value` | string | Selected value |
| `validation` | array | Validation rules (required, options) |
| `help` | string | Help text displayed after field |
| `attributes` | array | Custom HTML attributes |
| `wrapper_class` | string/array | Custom wrapper CSS classes |

### Validation Example

```php
'subscription' => array(
    'type'       => 'radio',
    'label'      => 'Subscription Plan',
    'options'    => array(
        'free'    => 'Free',
        'basic'   => 'Basic ($9/mo)',
        'premium' => 'Premium ($19/mo)',
    ),
    'validation' => array(
        'required' => true,
    ),
    'errors'     => array(
        'required' => 'Please select a subscription plan',
    ),
),
```

## Checkbox Field

### Description
Single checkbox for yes/no choices, agreements, or opt-in scenarios. The value is `'1'` when checked, empty when unchecked.

### Basic Usage

```php
$schema = array(
    'agree_terms' => array(
        'type'       => 'checkbox',
        'label'      => 'I agree to the terms and conditions',
        'value'      => false,
        'validation' => array(
            'required' => true,
        ),
    ),
);
```

### Features

- **Boolean-style value** - Checked = `'1'`, Unchecked = `''` (empty)
- **Required support** - Can require checkbox to be checked
- **No options** - Unlike checkbox_group, this is a single checkbox
- **Simple validation** - Validates presence when required

### Schema Options

| Option | Type | Description |
|--------|------|-------------|
| `type` | string | Must be `'checkbox'` |
| `label` | string | Field label text |
| `value` | mixed | `true`/`'1'` for checked, `false`/`''` for unchecked |
| `validation` | array | Validation rules (required) |
| `help` | string | Help text displayed after field |
| `attributes` | array | Custom HTML attributes |
| `wrapper_class` | string/array | Custom wrapper CSS classes |

### Examples

**Newsletter opt-in:**
```php
'newsletter' => array(
    'type'  => 'checkbox',
    'label' => 'Subscribe to newsletter',
    'value' => false,
    'help'  => 'Receive weekly updates',
),
```

**Required agreement:**
```php
'agree_privacy' => array(
    'type'       => 'checkbox',
    'label'      => 'I have read and agree to the privacy policy',
    'validation' => array(
        'required' => true,
    ),
    'errors'     => array(
        'required' => 'You must agree to the privacy policy',
    ),
),
```

## Field Comparison

| Feature | Radio | Checkbox | Checkbox_Group | Select |
|---------|-------|----------|----------------|--------|
| Selection | Single | Yes/No | Multiple | Single |
| Options | Required | None | Required | Required |
| Value type | String | String ('1' or '') | Array | String |
| Display | Inline options | Single box | Inline options | Dropdown |
| Use case | Choose one from many | Agree/Opt-in | Select multiple | Choose one (compact) |

## Complete Example

See `docs/radio-checkbox-example.php` for a working demonstration.

## Validation

Both field types integrate with the existing validation system:

**Radio validation:**
- Validates selected value is in options array
- Supports required validation
- Supports custom validation callbacks

**Checkbox validation:**
- Validates presence when required
- Treated as empty when value is `''` or `null`
- Treated as checked when value is non-empty

## Sanitization

**Radio fields:**
- Sanitized with `sanitize_text_field()`
- Value must match one of the provided options

**Checkbox fields:**
- Sanitized with `sanitize_text_field()`
- Returns `'1'` when checked, `''` when unchecked

## HTML Output

**Radio field output:**
```html
<div id="form-prefix-field-name-wrap" class="has-required">
    <label for="form-prefix-field-name">Gender</label>
    <div id="form-prefix-field-name" class="radio-group">
        <label for="form-prefix-field-name-male">
            <input id="form-prefix-field-name-male" type="radio" name="form_prefix[field_name]" value="male" checked="checked"> Male
        </label>
        <label for="form-prefix-field-name-female">
            <input id="form-prefix-field-name-female" type="radio" name="form_prefix[field_name]" value="female"> Female
        </label>
    </div>
</div>
```

**Checkbox field output:**
```html
<div id="form-prefix-agree-terms-wrap" class="has-required">
    <label for="form-prefix-agree-terms">I agree to the terms</label>
    <input id="form-prefix-agree-terms" name="form_prefix[agree_terms]" type="checkbox" value="1" />
</div>
```
