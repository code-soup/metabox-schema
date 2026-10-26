# Wrapper Features - Implementation Examples

This document demonstrates the three new wrapper features implemented in metabox-schema.

## Feature 1: Custom Wrapper Classes

### String Format

```php
$schema = array(
    'email' => array(
        'type'          => 'email',
        'label'         => 'Email Address',
        'wrapper_class' => 'custom-field highlighted',
        'help'          => 'Enter your email address',
    ),
);
```

**Output:**
```html
<div id="form-email-wrap" class="custom-field highlighted">
    <label for="form-email">Email Address</label>
    <input type="email" id="form-email" name="form[email]" />
    <small>Enter your email address</small>
</div>
```

### Array Format

```php
$schema = array(
    'username' => array(
        'type'          => 'text',
        'label'         => 'Username',
        'wrapper_class' => array( 'field-wrapper', 'user-input', 'primary' ),
        'help'          => 'Choose a unique username',
    ),
);
```

**Output:**
```html
<div id="form-username-wrap" class="field-wrapper user-input primary">
    <label for="form-username">Username</label>
    <input type="text" id="form-username" name="form[username]" />
    <small>Choose a unique username</small>
</div>
```

## Feature 2: Automatic Required Class

### Default Required Class

```php
$schema = array(
    'password' => array(
        'type'       => 'password',
        'label'      => 'Password',
        'validation' => array( 'required' => true ),
        'help'       => 'Minimum 8 characters',
    ),
);
```

**Output:**
```html
<div id="form-password-wrap" class="has-required">
    <label for="form-password">Password</label>
    <input type="password" id="form-password" name="form[password]" required />
    <small>Minimum 8 characters</small>
</div>
```

### Custom Required Class (Field-Level Override)

```php
$schema = array(
    'phone' => array(
        'type'           => 'tel',
        'label'          => 'Phone Number',
        'validation'     => array( 'required' => true ),
        'required_class' => 'mandatory-field',
        'help'           => 'Include country code',
    ),
);
```

**Output:**
```html
<div id="form-phone-wrap" class="mandatory-field">
    <label for="form-phone">Phone Number</label>
    <input type="tel" id="form-phone" name="form[phone]" required />
    <small>Include country code</small>
</div>
```

### Global Default Required Class

```php
Renderer::render(
    array(
        'schema' => array(
            'bio' => array(
                'type'       => 'textarea',
                'label'      => 'Biography',
                'validation' => array( 'required' => true ),
            ),
        ),
        'form_prefix'            => 'profile',
        'default_required_class' => 'is-required',
    )
);
```

**Output:**
```html
<div id="profile-bio-wrap" class="is-required">
    <label for="profile-bio">Biography</label>
    <textarea id="profile-bio" name="profile[bio]" rows="5" required></textarea>
</div>
```

## Feature 3: Help Text Position

### Help After Field (Default)

```php
$schema = array(
    'website' => array(
        'type'  => 'url',
        'label' => 'Website',
        'help'  => 'Enter your website URL',
    ),
);
```

**Output:**
```html
<div id="form-website-wrap">
    <label for="form-website">Website</label>
    <input type="url" id="form-website" name="form[website]" />
    <small>Enter your website URL</small>
</div>
```

### Help Before Field

```php
$schema = array(
    'twitter' => array(
        'type'          => 'text',
        'label'         => 'Twitter Handle',
        'help'          => 'Do not include @ symbol',
        'help_position' => 'before',
    ),
);
```

**Output:**
```html
<div id="form-twitter-wrap">
    <label for="form-twitter">Twitter Handle</label>
    <small>Do not include @ symbol</small>
    <input type="text" id="form-twitter" name="form[twitter]" />
</div>
```

### Global Default Help Position

```php
Renderer::render(
    array(
        'schema' => array(
            'github' => array(
                'type'  => 'text',
                'label' => 'GitHub Username',
                'help'  => 'Your GitHub username',
            ),
        ),
        'form_prefix'           => 'social',
        'default_help_position' => 'before',
    )
);
```

**Output:**
```html
<div id="social-github-wrap">
    <label for="social-github">GitHub Username</label>
    <small>Your GitHub username</small>
    <input type="text" id="social-github" name="social[github]" />
</div>
```

## Combined Features Example

```php
$schema = array(
    'full_name' => array(
        'type'          => 'text',
        'label'         => 'Full Name',
        'wrapper_class' => array( 'form-field', 'important' ),
        'validation'    => array( 'required' => true ),
        'help'          => 'Enter your legal name',
        'help_position' => 'before',
    ),
);
```

**Output:**
```html
<div id="form-full-name-wrap" class="form-field important has-required">
    <label for="form-full-name">Full Name</label>
    <small>Enter your legal name</small>
    <input type="text" id="form-full-name" name="form[full_name]" required />
</div>
```
