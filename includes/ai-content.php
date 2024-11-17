<?php
/**
 * Generate blog post content using OpenAI GPT-4 based on given keywords.
 *
 * @return array Result of the content generation, including success status and message.
 */
function ai_blog_gen_generate_post_content() {
    // Retrieve API key and keywords from the database
    $api_key = get_option('ai_blog_gen_api_key', '');
    $keywords = get_option('ai_blog_gen_keywords', '');

    // Check if API key or keywords are empty
    if (empty($api_key) || empty($keywords)) {
        return array(
            'success' => false,
            'message' => 'API key or keywords are missing. Please update the settings and try again.'
        );
    }

    // Define the OpenAI GPT-4 endpoint and headers
    $endpoint = 'https://api.openai.com/v1/chat/completions';
    $headers = array(
        'Authorization' => 'Bearer ' . $api_key,
        'Content-Type'  => 'application/json'
    );

    // Define the prompt for content generation in chat format
    $messages = array(
        array(
            'role' => 'system',
            'content' => 'You are a helpful assistant that generates blog posts based on provided keywords.'
        ),
        array(
            'role' => 'user',
            'content' => 'Write a detailed blog post using these keywords: ' . $keywords
        )
    );

    // Set up the API request body
    $body = json_encode(array(
        'model' => 'gpt-4o-mini',   // Use GPT-4 model
        'messages' => $messages,       // Pass the conversation as an array of messages
        'max_tokens' => 800,           // Set a reasonable limit for content length
        'temperature' => 0.7           // Control the randomness of the generated content
    ));

    // Make the API request
    $response = wp_remote_post($endpoint, array(
        'headers' => $headers,
        'body'    => $body
    ));

    // Check for errors in the response
    if (is_wp_error($response)) {
        return array(
            'success' => false,
            'message' => 'Error connecting to OpenAI: ' . $response->get_error_message()
        );
    }

    // Retrieve and decode the response body
    $body = json_decode(wp_remote_retrieve_body($response), true);

    // Check if generation was successful
    if (isset($body['choices'][0]['message']['content'])) {
        return array(
            'success' => true,
            'content' => $body['choices'][0]['message']['content']
        );
    } else {
        // Log detailed error message from OpenAI response
        $error_message = isset($body['error']['message']) ? $body['error']['message'] : 'Unknown error occurred.';
        return array(
            'success' => false,
            'message' => 'Content generation failed: ' . $error_message
        );
    }
}

/**
 * Generate and save a blog post draft in WordPress.
 *
 * @return array Status and message of the save operation.
 */
function ai_blog_gen_save_draft() {
    // Generate the content
    $generation_result = ai_blog_gen_generate_post_content();

    // Check if generation was successful
    if (!$generation_result['success']) {
        return array(
            'success' => false,
            'message' => $generation_result['message']
        );
    }

    // Retrieve the generated content
    $content = $generation_result['content'];
    $title = 'AI Generated Blog Post - ' . date('Y-m-d H:i:s');

    // Prepare post data
    $post_data = array(
        'post_title'   => wp_strip_all_tags($title),
        'post_content' => $content,
        'post_status'  => 'draft',
        'post_type'    => 'post'
    );

    // Insert the post into the database
    $post_id = wp_insert_post($post_data);

    // Check if the post was saved successfully
    if ($post_id === 0) {
        return array(
            'success' => false,
            'message' => 'Failed to save the generated content as a draft.'
        );
    }

    return array(
        'success' => true,
        'message' => 'Blog post generated and saved as draft successfully!'
    );
}
