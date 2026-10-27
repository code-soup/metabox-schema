# Radio and Checkbox Fields

## Radio (single choice)

```php
'plan' => array(
    'type'       => 'radio',
    'label'      => 'Subscription plan',
    'options'    => array(
        'free'    => 'Free',
        'basic'   => 'Basic',
        'premium' => 'Premium',
    ),
    'value'      => 'free',
    'validation' => array(
        'required' => true,
    ),
),
```

Submitted values are validated against `options` (same as `select`).

## Checkbox (single yes/no)

```php
'agree_terms' => array(
    'type'       => 'checkbox',
    'label'      => 'I agree to the terms',
    'value'      => false,
    'validation' => array(
        'required' => true,
    ),
),
```

Checked submissions use value `'1'`; unchecked fields are omitted from `$_POST` (empty).

See `docs/radio-checkbox-fields.md` in the package for more examples.
