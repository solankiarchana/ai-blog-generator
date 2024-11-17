<?php
// Prevent direct access to the file
if (!defined('ABSPATH')) exit;

/**
 * Registers settings and adds settings sections and fields.
 */
function ai_blog_gen_register_settings() {
    // Register settings
    register_setting('ai_blog_gen_options', 'ai_blog_gen_api_key');
    register_setting('ai_blog_gen_options', 'ai_blog_gen_keywords');

    // Add settings section
    add_settings_section(
        'ai_blog_gen_main_section',          // Section ID
        'API Settings',                      // Title
        'ai_blog_gen_section_description',   // Callback function
        'ai_blog_generator'                  // Page
    );

    // Add API key field
    add_settings_field(
        'ai_blog_gen_api_key',               // Field ID
        'API Key',                           // Title
        'ai_blog_gen_api_key_field',         // Callback function to display field
        'ai_blog_generator',                 // Page
        'ai_blog_gen_main_section'           // Section ID
    );

    // Add keywords field
    add_settings_field(
        'ai_blog_gen_keywords',              // Field ID
        'Keywords',                          // Title
        'ai_blog_gen_keywords_field',        // Callback function to display field
        'ai_blog_generator',                 // Page
        'ai_blog_gen_main_section'           // Section ID
    );
}
add_action('admin_init', 'ai_blog_gen_register_settings');

/**
 * Description for the main settings section.
 */
function ai_blog_gen_section_description() {
    echo '<p>Enter your API details to generate AI-powered blog posts.</p>';
}

/**
 * Displays the API Key field.
 */
function ai_blog_gen_api_key_field() {
    $api_key = get_option('ai_blog_gen_api_key', '');
    echo '<input type="text" name="ai_blog_gen_api_key" value="' . esc_attr($api_key) . '" size="50">';
}

/**
 * Displays the Keywords field.
 */
function ai_blog_gen_keywords_field() {
    $keywords = get_option('ai_blog_gen_keywords', '');
    echo '<input type="text" name="ai_blog_gen_keywords" value="' . esc_attr($keywords) . '" size="50">';
}
