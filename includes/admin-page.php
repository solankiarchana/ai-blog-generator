<?php
// Prevent direct access to the file
if (!defined('ABSPATH')) exit;

/**
 * Registers a menu item in the WordPress admin sidebar.
 */
function ai_blog_gen_add_admin_menu() {
    add_menu_page(
        'AI Blog Generator',       // Page title
        'AI Blog Generator',       // Menu title
        'manage_options',          // Capability required to access the page
        'ai_blog_generator',       // Menu slug
        'ai_blog_gen_settings_page', // Callback function to display the page content
        'dashicons-edit',          // Icon for the menu item
        80                         // Position in the menu order
    );
}
add_action('admin_menu', 'ai_blog_gen_add_admin_menu');

/**
 * Displays the content of the settings page.
 */
function ai_blog_gen_settings_page() {
    ?>
    <div class="wrap">
        <h1>AI Blog Generator Settings</h1>
        <form method="post" action="options.php">
            <?php
                settings_fields('ai_blog_gen_options'); // Security field for settings form
                do_settings_sections('ai_blog_generator'); // Output settings sections and fields
                submit_button(); // Generates a Save Changes button

                // Display API validation message
                echo '<h2>API Key Validation</h2>';
                $validation_message = ai_blog_gen_validate_api_key();
                echo '<p>' . esc_html($validation_message) . '</p>';
            ?>
        </form>
        <?php
            // Generate and save blog post on button click
            if (isset($_POST['generate_blog_post'])) {
                $result = ai_blog_gen_save_draft();
                echo '<p>' . esc_html($result['message']) . '</p>';
            }
            ?>

            <form method="post">
                <input type="hidden" name="generate_blog_post" value="1">
                <?php submit_button('Generate Blog Post'); ?>
            </form>
        </div>
    </div>
    <?php
}