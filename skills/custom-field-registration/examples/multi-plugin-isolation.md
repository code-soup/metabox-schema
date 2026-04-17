# Multi-Plugin Isolation Example

## Problem: Plugin Conflicts with Static Registration

```php
// Plugin A: Contact Forms
class Contact_Forms_WYSIWYG extends Abstract_Field {
    protected function get_template_name(): string {
        return 'simple-wysiwyg';
    }
}

// Plugin A registers globally (OLD WAY - CAUSES CONFLICTS)
Field_Factory::register_field_type( 'wysiwyg', Contact_Forms_WYSIWYG::class );

// Plugin B: Page Builder
class Page_Builder_WYSIWYG extends Abstract_Field {
    protected function get_template_name(): string {
        return 'advanced-wysiwyg';
    }
}

// Plugin B overwrites Plugin A's registration!
Field_Factory::register_field_type( 'wysiwyg', Page_Builder_WYSIWYG::class );

// Plugin A renders form - BROKEN! Uses Plugin B's field instead
Renderer::render( $schema );
```

## Solution: Instance-Based Registration

```php
// Plugin A: Contact Forms
class Contact_Forms_WYSIWYG extends Abstract_Field {
    protected function get_template_name(): string {
        return 'simple-wysiwyg';
    }
}

class Contact_Forms_Plugin {
    private Renderer $renderer;
    
    public function __construct() {
        $this->renderer = new Renderer();
        $this->renderer->register_field_type( 'wysiwyg', Contact_Forms_WYSIWYG::class );
    }
    
    public function render_contact_form() {
        $this->renderer->render_fields([
            'schema' => [
                'message' => [ 'type' => 'wysiwyg', 'label' => 'Message' ]
            ],
            'form_prefix' => 'contact'
        ]);
    }
}

// Plugin B: Page Builder
class Page_Builder_WYSIWYG extends Abstract_Field {
    protected function get_template_name(): string {
        return 'advanced-wysiwyg';
    }
}

class Page_Builder_Plugin {
    private Renderer $renderer;
    
    public function __construct() {
        $this->renderer = new Renderer();
        $this->renderer->register_field_type( 'wysiwyg', Page_Builder_WYSIWYG::class );
    }
    
    public function render_page_content() {
        $this->renderer->render_fields([
            'schema' => [
                'content' => [ 'type' => 'wysiwyg', 'label' => 'Page Content' ]
            ],
            'form_prefix' => 'page'
        ]);
    }
}

// Both plugins work independently - NO CONFLICTS!
$contact_forms = new Contact_Forms_Plugin();
$contact_forms->render_contact_form(); // Uses Contact_Forms_WYSIWYG

$page_builder = new Page_Builder_Plugin();
$page_builder->render_page_content(); // Uses Page_Builder_WYSIWYG
```

## Benefits

✅ **No Conflicts** - Each renderer has isolated registry  
✅ **Safe** - One compromised plugin can't break another  
✅ **Predictable** - Field type is always what you registered  
✅ **Testable** - Easy to test in isolation  

## Migration from Static to Instance

### Before (Static - Removed in v1.1.0)

```php
// This no longer works - Field_Factory::register_field_type() removed
Field_Factory::register_field_type( 'custom', Custom_Field::class );
Renderer::render( $config );
```

### After (Instance-Based - Required)

```php
$renderer = new Renderer();
$renderer->register_field_type( 'custom', Custom_Field::class );
$renderer->render_fields( $config );
```
