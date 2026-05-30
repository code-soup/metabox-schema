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

## Checkbox Group Field

```php
'categories' => array(
    'type'           => 'checkbox_group',
    'label'          => 'Categories',
    'options'        => array(
        'tech'     => 'Technology',
        'design'   => 'Design',
        'business' => 'Business',
        'health'   => 'Health',
        'food'     => 'Food',
    ),
    'sort_az'        => true,  // Sort options alphabetically
    'checked_on_top' => true,  // Show checked options first
    'validation'     => array(
        'required' => true,
    ),
),
```

**Options:**
- `options` - Key-value pairs (value => label)
- `sort_az` - Sort options alphabetically by label (default: false)
- `checked_on_top` - Group checked options at the top (default: false)
- When both `sort_az` and `checked_on_top` are true, options are sorted alphabetically within each group (checked/unchecked)

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
    'bio'        => array(
        'type'  => 'textarea',
        'label' => 'Biography',
        'rows'  => 5,
    ),
    'categories' => array(
        'type'           => 'checkbox_group',
        'label'          => 'Categories',
        'options'        => array(
            'tech'     => 'Technology',
            'design'   => 'Design',
            'business' => 'Business',
            'health'   => 'Health',
            'food'     => 'Food',
        ),
        'sort_az'        => true,
        'checked_on_top' => true,
        'validation'     => array(
            'required' => true,
        ),
    ),
);
```

