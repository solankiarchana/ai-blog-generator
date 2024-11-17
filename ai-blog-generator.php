<?php
/*
Plugin Name: AI Blog Generator
Plugin URI: https://example.com
Description: A plugin to generate blog posts using AI, based on keywords.
Version: 1.0
Author: Your Name
Author URI: https://example.com
License: GPL2
*/

// Prevent direct access to the file
if (!defined('ABSPATH')) exit;

// Include files
include_once plugin_dir_path(__FILE__) . 'includes/settings.php';
include_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';
include_once plugin_dir_path(__FILE__) . 'includes/ai-content.php';
include_once plugin_dir_path(__FILE__) . 'includes/api-integration.php';

// Activation and Deactivation hooks
function ai_blog_gen_activate() {
    // Code to run on activation, such as setting default options
}
register_activation_hook(__FILE__, 'ai_blog_gen_activate');

function ai_blog_gen_deactivate() {
    // Remove saved plugin settings, if needed
    delete_option('ai_blog_gen_api_key'); // Removes the stored API key
    delete_option('ai_blog_gen_keywords'); // Removes stored keywords
}
register_deactivation_hook(__FILE__, 'ai_blog_gen_deactivate');

