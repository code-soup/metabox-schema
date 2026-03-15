# Basic Schema Example

Demonstrates basic field schema structure.

## Text Field with Validation

```php
'username' => array(
    'type'       => 'text',
    'label'      => 'Username',
    'attributes' => array(
        'class'       => 'regular-text',
        'placeholder' => 'Enter username',
    ),
    'validation' => array(
        'required' => true,
        'min'      => 3,
        'max'      => 50,
    ),
),
```

## Email Field

```php
'email' => array(
    'type'       => 'email',
    'label'      => 'Email Address',
    'validation' => array(
        'required' => true,
    ),
),
```

## Textarea Field

```php
'bio' => array(
    'type'  => 'textarea',
    'label' => 'Biography',
    'rows'  => 5,
),
```

## Complete Schema

```php
$schema = array(
    'username' => array(
        'type'       => 'text',
        'label'      => 'Username',
        'attributes' => array(
            'class'       => 'regular-text',
            'placeholder' => 'Enter username',
        ),
        'validation' => array(
            'required' => true,
            'min'      => 3,
            'max'      => 50,
        ),
    ),
    'email'    => array(
        'type'       => 'email',
        'label'      => 'Email Address',
        'validation' => array(
            'required' => true,
        ),
    ),
    'bio'      => array(
        'type'  => 'textarea',
        'label' => 'Biography',
        'rows'  => 5,
    ),
);
```

