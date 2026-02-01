<?php
/**
 * WordPress Metabox Example - Product Details
 *
 * WHAT THIS FILE DOES:
 * This class creates a custom metabox for WordPress product posts.
 * It uses CodeSoup\MetaboxSchema to render form fields and validate data.
 *
 * HOW IT WORKS:
 * 1. Loads field definitions from a separate schema file
 * 2. Registers a metabox that appears on product edit screens
 * 3. Renders form fields automatically from the schema
 * 4. Validates submitted data when the post is saved
 * 5. Displays error messages if validation fails
 * 6. Saves validated data to post meta
 *
 * USAGE:
 * Simply instantiate this class in your plugin or theme:
 * new ProductDetailsMetabox();
 *
 * REQUIREMENTS:
 * - WordPress with custom post type 'product' registered
 * - CodeSoup\MetaboxSchema package installed via Composer
 * - Schema file at: examples/wp/schema.php
 *
 * KEY CONCEPTS:
 * - Constants (METABOX_ID, etc.) are configuration values that never change
 * - Private methods can only be called inside this class
 * - Public methods can be called by WordPress hooks
 * - The schema array defines what fields to show and how to validate them
 * - Nonces are security tokens that prevent unauthorized form submissions
 * - Transients are temporary data stored in WordPress database
 *
 * @package CodeSoup\MetaboxSchema
 */

use CodeSoup\MetaboxSchema\Renderer;
use CodeSoup\MetaboxSchema\Validator;

class ProductDetailsMetabox {

	/**
	 * Unique identifier for this metabox.
	 * Used in HTML and WordPress registration.
	 */
	private const METABOX_ID = 'product_details';

	/**
	 * Security action name for nonce verification.
	 * Prevents unauthorized form submissions.
	 */
	private const NONCE_ACTION = 'product_details_nonce';

	/**
	 * Name of the nonce field in the form.
	 * WordPress uses this to verify the form came from our metabox.
	 */
	private const NONCE_NAME = 'product_details_nonce';

	/**
	 * Prefix for form field names.
	 * All fields will be submitted as $_POST['product_meta']['field_name'].
	 */
	private const FORM_PREFIX = 'product_meta';

	/**
	 * WordPress post type this metabox appears on.
	 * Change this to match your custom post type.
	 */
	private const POST_TYPE = 'product';

	/**
	 * Schema array defining all fields, validation rules, and labels.
	 * Loaded from external file in constructor.
	 *
	 * @var array
	 */
	private array $schema;

	/**
	 * Constructor - runs when class is instantiated.
	 *
	 * WHAT IT DOES:
	 * 1. Loads the schema from external file
	 * 2. Registers WordPress hooks to make the metabox work
	 *
	 * NOTE:
	 * This method runs automatically when you write: new ProductDetailsMetabox();
	 */
	public function __construct() {
		$this->schema = $this->loadSchema();
		$this->registerHooks();
	}

	/**
	 * Load schema from external file.
	 *
	 * WHAT IT DOES:
	 * Loads the field definitions from wp/schema.php.
	 * The schema defines what fields to show and how to validate them.
	 *
	 * WHY SEPARATE FILE:
	 * - Keeps field definitions organized
	 * - Makes schema reusable
	 * - Easier to maintain and update fields
	 *
	 * @return array Schema array with field definitions.
	 */
	private function loadSchema(): array {
		return require __DIR__ . '/wp/schema.php';
	}

	/**
	 * Register WordPress hooks.
	 *
	 * WHAT IT DOES:
	 * Tells WordPress when to call our methods:
	 * - add_meta_boxes: When to show the metabox
	 * - save_post_{post_type}: When to save the data
	 * - admin_notices: When to display error messages
	 *
	 * NOTE:
	 * Hooks are how WordPress lets you run code at specific times.
	 * array($this, 'methodName') tells WordPress to call a method in this class.
	 */
	private function registerHooks(): void {
		add_action(
			'add_meta_boxes',
			array(
				$this,
				'registerMetabox',
			)
		);

		add_action(
			sprintf(
				'save_post_%s',
				self::POST_TYPE
			),
			array(
				$this,
				'saveMetabox',
			)
		);

		add_action(
			'admin_notices',
			array(
				$this,
				'displayErrors',
			)
		);
	}

	/**
	 * Register the metabox with WordPress.
	 *
	 * WHAT IT DOES:
	 * Tells WordPress to create a metabox on product edit screens.
	 *
	 * PARAMETERS EXPLAINED:
	 * - METABOX_ID: Unique ID for this metabox
	 * - 'Product Details': Title shown at top of metabox
	 * - renderMetabox: Method that outputs the HTML
	 * - POST_TYPE: Which post type to show on (product)
	 * - 'normal': Position (normal, side, advanced)
	 * - 'high': Priority (high, default, low)
	 *
	 * NOTE:
	 * This is called by WordPress via the add_meta_boxes hook.
	 * You do not call this method directly.
	 */
	public function registerMetabox(): void {
		add_meta_box(
			self::METABOX_ID,
			__(
				'Product Details',
				'your-text-domain'
			),
			array(
				$this,
				'renderMetabox',
			),
			self::POST_TYPE,
			'normal',
			'high'
		);
	}

	/**
	 * Render the metabox HTML.
	 *
	 * WHAT IT DOES:
	 * 1. Adds a security nonce field
	 * 2. Uses Renderer to generate form fields from schema
	 *
	 * WHY NONCE:
	 * Security token that proves the form came from WordPress admin.
	 * Prevents malicious form submissions from other websites.
	 *
	 * RENDERER PARAMETERS:
	 * - schema: Field definitions (what to show)
	 * - entity: WordPress post object (not used in this example)
	 * - form_prefix: Prefix for field names in $_POST
	 *
	 * NOTE:
	 * WordPress calls this method when displaying the edit screen.
	 * The $post parameter is automatically provided by WordPress.
	 *
	 * @param WP_Post $post Current post object being edited.
	 */
	public function renderMetabox( $post ): void {
		wp_nonce_field(
			self::NONCE_ACTION,
			self::NONCE_NAME
		);

		Renderer::render(
			array(
				'schema'      => $this->schema,
				'entity'      => $post,
				'form_prefix' => self::FORM_PREFIX,
			)
		);
	}

	/**
	 * Save metabox data when post is saved.
	 *
	 * WHAT IT DOES:
	 * 1. Checks if we should save (security and permissions)
	 * 2. Validates submitted data against schema rules
	 * 3. If validation fails: stores errors and stops
	 * 4. If validation passes: saves data to post meta
	 *
	 * WORKFLOW:
	 * User clicks "Update" → WordPress saves post → This method runs
	 *
	 * WHY VALIDATION:
	 * Prevents bad data from being saved to database.
	 * Example: Ensures price is a number, SKU matches pattern, etc.
	 *
	 * NOTE:
	 * WordPress calls this automatically via save_post_{post_type} hook.
	 * The $post_id parameter is provided by WordPress.
	 *
	 * @param int $post_id ID of the post being saved.
	 */
	public function saveMetabox( int $post_id ): void {
		if ( ! $this->shouldSave( $post_id ) ) {
			return;
		}

		$validator = new Validator();
		$validated_data = $validator->validate(
			$_POST[ self::FORM_PREFIX ],
			$this->schema
		);

		if ( $validator->hasErrors() ) {
			$this->storeErrors(
				$post_id,
				$validator->getErrors()
			);
			return;
		}

		$this->saveData(
			$post_id,
			$validated_data
		);

		$this->clearErrors( $post_id );
	}

	/**
	 * Check if we should save the metabox data.
	 *
	 * WHAT IT DOES:
	 * Performs security and validation checks before saving.
	 * Returns true only if all checks pass.
	 *
	 * CHECKS PERFORMED:
	 * 1. Nonce exists: Form came from our metabox
	 * 2. Nonce is valid: Security token is correct
	 * 3. Not autosave: WordPress auto-save should not trigger this
	 * 4. User has permission: Current user can edit this post
	 * 5. Data exists: Form data was actually submitted
	 *
	 * WHY THESE CHECKS:
	 * - Prevents unauthorized users from saving data
	 * - Prevents malicious form submissions
	 * - Prevents saving during auto-save (which happens every 60 seconds)
	 *
	 * NOTE:
	 * If any check fails, we return false and stop saving.
	 * This is called a "guard clause" pattern.
	 *
	 * @param int $post_id ID of the post being saved.
	 * @return bool True if we should save, false otherwise.
	 */
	private function shouldSave( int $post_id ): bool {
		if ( ! isset( $_POST[ self::NONCE_NAME ] ) ) {
			return false;
		}

		if ( ! wp_verify_nonce( $_POST[ self::NONCE_NAME ], self::NONCE_ACTION ) ) {
			return false;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return false;
		}

		if ( ! isset( $_POST[ self::FORM_PREFIX ] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Store validation errors temporarily.
	 *
	 * WHAT IT DOES:
	 * Saves error messages to WordPress transient (temporary storage).
	 * Errors are displayed on next page load, then automatically deleted.
	 *
	 * WHY TRANSIENTS:
	 * After saving, WordPress redirects to avoid duplicate submissions.
	 * Transients let us pass error messages across the redirect.
	 *
	 * EXPIRATION:
	 * Errors expire after 45 seconds if not displayed.
	 *
	 * NOTE:
	 * Transients are like temporary variables stored in the database.
	 * They automatically delete themselves after a set time.
	 *
	 * @param int   $post_id ID of the post.
	 * @param array $errors  Array of error messages from validator.
	 */
	private function storeErrors( int $post_id, array $errors ): void {
		set_transient(
			$this->getErrorTransientKey( $post_id ),
			$errors,
			45
		);
	}

	/**
	 * Clear stored validation errors.
	 *
	 * WHAT IT DOES:
	 * Removes error messages from temporary storage.
	 * Called after successful save.
	 *
	 * WHY:
	 * Prevents old errors from showing after data is fixed and saved.
	 *
	 * @param int $post_id ID of the post.
	 */
	private function clearErrors( int $post_id ): void {
		delete_transient(
			$this->getErrorTransientKey( $post_id )
		);
	}

	/**
	 * Generate unique key for error transient.
	 *
	 * WHAT IT DOES:
	 * Creates a unique identifier for storing errors.
	 * Format: product_details_errors_123
	 *
	 * WHY UNIQUE:
	 * Each post needs its own error storage.
	 * Prevents errors from one post showing on another.
	 *
	 * @param int $post_id ID of the post.
	 * @return string Transient key.
	 */
	private function getErrorTransientKey( int $post_id ): string {
		return sprintf(
			'%s_errors_%d',
			self::METABOX_ID,
			$post_id
		);
	}

	/**
	 * Save validated data to post meta.
	 *
	 * WHAT IT DOES:
	 * Loops through validated data and saves each field to post meta.
	 *
	 * POST META:
	 * WordPress way of storing custom data for posts.
	 * Each field is saved as: meta_key => meta_value
	 *
	 * WHY LOOP:
	 * Schema can have any number of fields.
	 * Loop handles them all automatically.
	 *
	 * NOTE:
	 * update_post_meta() creates or updates a meta field.
	 * If the field exists, it updates. If not, it creates.
	 *
	 * @param int   $post_id ID of the post.
	 * @param array $data    Validated data from validator.
	 */
	private function saveData( int $post_id, array $data ): void {
		foreach ( $data as $key => $value ) {
			update_post_meta(
				$post_id,
				$key,
				$value
			);
		}
	}

	/**
	 * Display validation errors in admin.
	 *
	 * WHAT IT DOES:
	 * Shows error messages at top of edit screen if validation failed.
	 *
	 * WHEN IT RUNS:
	 * WordPress calls this via admin_notices hook on every admin page.
	 *
	 * CHECKS:
	 * 1. Is there a post being edited?
	 * 2. Is it the correct post type?
	 * 3. Are there stored errors for this post?
	 *
	 * WHY CHECK POST TYPE:
	 * This hook runs on ALL admin pages.
	 * We only want to show errors on product edit screens.
	 *
	 * NOTE:
	 * global $post gives us the current post being edited.
	 * If checks fail, we return early (do nothing).
	 */
	public function displayErrors(): void {
		global $post;

		if ( ! $post || self::POST_TYPE !== $post->post_type ) {
			return;
		}

		$errors = get_transient(
			$this->getErrorTransientKey( $post->ID )
		);

		if ( ! $errors ) {
			return;
		}

		$this->renderErrorNotice( $errors );
	}

	/**
	 * Render error notice HTML.
	 *
	 * WHAT IT DOES:
	 * Outputs HTML for WordPress admin notice with error messages.
	 *
	 * NOTICE CLASSES:
	 * - notice: WordPress admin notice styling
	 * - notice-error: Red error styling
	 * - is-dismissible: Adds X button to close notice
	 *
	 * SECURITY:
	 * esc_html() prevents XSS attacks by escaping HTML characters.
	 * Always escape output in WordPress.
	 *
	 * NOTE:
	 * This creates the red error box you see at top of admin pages.
	 * Each validation error is shown as a list item.
	 *
	 * @param array $errors Array of error messages (field => message).
	 */
	private function renderErrorNotice( array $errors ): void {
		echo '<div class="notice notice-error is-dismissible">';
		printf(
			'<p><strong>%s</strong></p>',
			esc_html__(
				'Product details validation failed:',
				'your-text-domain'
			)
		);
		echo '<ul>';
		foreach ( $errors as $field => $error ) {
			printf(
				'<li>%s</li>',
				esc_html( $error )
			);
		}
		echo '</ul>';
		echo '</div>';
	}
}

/**
 * Initialize the metabox.
 *
 * WHAT THIS DOES:
 * Creates an instance of the class, which triggers the constructor.
 * The constructor loads the schema and registers WordPress hooks.
 *
 * NOTE:
 * This single line makes everything work.
 * Place this in your plugin or theme's main file.
 */
new ProductDetailsMetabox();

