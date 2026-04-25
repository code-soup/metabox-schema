# Select Field with Optgroups

Demonstrates optgroup support in select fields.

## Flat Options

```php
'country' => array(
    'type'    => 'select',
    'label'   => 'Country',
    'options' => array(
        'us' => 'United States',
        'uk' => 'United Kingdom',
        'ca' => 'Canada',
    ),
),
```

Renders standard `<select>` with flat `<option>` elements.

## Grouped Options

```php
'country' => array(
    'type'    => 'select',
    'label'   => 'Country',
    'options' => array(
        ''                => '— Select Country —',
        'North America'   => array(
            'us' => 'United States',
            'ca' => 'Canada',
            'mx' => 'Mexico',
        ),
        'Europe'          => array(
            'uk' => 'United Kingdom',
            'de' => 'Germany',
            'fr' => 'France',
        ),
        'Asia & Pacific'  => array(
            'au' => 'Australia',
            'jp' => 'Japan',
            'cn' => 'China',
        ),
    ),
),
```

Renders:
```html
<select id="form-country" name="form[country]">
    <option value="">— Select Country —</option>
    <optgroup label="North America">
        <option value="us">United States</option>
        <option value="ca">Canada</option>
        <option value="mx">Mexico</option>
    </optgroup>
    <optgroup label="Europe">
        <option value="uk">United Kingdom</option>
        <option value="de">Germany</option>
        <option value="fr">France</option>
    </optgroup>
    <optgroup label="Asia & Pacific">
        <option value="au">Australia</option>
        <option value="jp">Japan</option>
        <option value="cn">China</option>
    </optgroup>
</select>
```

## Mixed Flat and Grouped

```php
'status' => array(
    'type'    => 'select',
    'label'   => 'Status',
    'options' => array(
        'draft'      => 'Draft',
        'Active'     => array(
            'pending' => 'Pending Review',
            'live'    => 'Published',
        ),
        'Archived'   => array(
            'archived'  => 'Archived',
            'deleted'   => 'Deleted',
        ),
    ),
),
```

Flat options mixed with optgroups work correctly.

## Validation

Optgroup validation works automatically:

```php
'country' => array(
    'type'       => 'select',
    'label'      => 'Country',
    'validation' => array(
        'required' => true,
    ),
    'options'    => array(
        'North America' => array(
            'us' => 'United States',
            'ca' => 'Canada',
        ),
    ),
),
```

Validator checks nested arrays for valid option values.

## Sanitization

Config_Sanitizer automatically handles nested arrays:

- Optgroup labels sanitized with `sanitize_text_field()`
- Option values sanitized with `sanitize_text_field()`
- Option labels sanitized with `sanitize_text_field()`
