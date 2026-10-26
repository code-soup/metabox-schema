=== Metabox Schema ===
Contributors: codesoup
Tags: forms, schema, validation, metabox, fields
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 8.1
Stable tag: 1.3.0
License: MIT
License URI: https://opensource.org/licenses/MIT

Schema-driven form builder for WordPress. Define fields as PHP arrays, render forms, and validate submitted data against the same schema.

== Description ==

Metabox Schema helps WordPress developers build admin forms and metaboxes without ad-hoc HTML:

* Render fields from a PHP schema (text, select, radio, checkbox, checkbox group, media, editor, and more)
* Sanitize and validate `$_POST` data with the same schema
* Override templates and extend Renderer / Validator classes
* Register custom field types per Renderer instance (no global conflicts)

Install via Composer in your theme or plugin, or activate this package as a standalone plugin after running `composer install`.

== Installation ==

**Composer (recommended for themes and other plugins)**

1. `composer require codesoup/metabox-schema`
2. Require the Composer autoloader in your project.

**Standalone WordPress plugin**

1. Copy the plugin folder to `wp-content/plugins/metabox-schema/`
2. Run `composer install --no-dev` inside the plugin directory
3. Activate **Metabox Schema** in the WordPress admin

See the [GitHub repository](https://github.com/code-soup/metabox-schema) for examples in `docs/`.

== Frequently Asked Questions ==

= Does this plugin add admin screens by itself? =

No. It is a library. You use `Renderer` and `Validator` in your own plugin or theme code.

= What PHP version is required? =

PHP 8.1 or newer.

== Changelog ==

= 1.3.0 =
* Wrapper ID/class, help position, and required CSS class options
* Radio and checkbox field types
* WordPress plugin bootstrap and readme metadata for distribution
* Validator fixes for defaults, checkbox groups, and display field types

= 1.2.1 =
* Checkbox group bugfixes (documentation)

= 1.2.0 =
* Checkbox group field type
* Instance-based custom field registration

See CHANGELOG.md in the plugin root for full history.

== Upgrade Notice ==

= 1.3.0 =
Wrapper markup and new field types. Review custom templates if you override field wrappers. Custom field registration remains instance-based (since 1.1.0).
