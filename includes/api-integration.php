<?php
/**
 * Validates the OpenAI API key by making a test request to the OpenAI API.
 *
 * @return string Success or error message based on the response.
 */
function ai_blog_gen_validate_api_key() {
    // Retrieve the saved API key from the database
    $api_key = get_option('ai_blog_gen_api_key', '');

    // If API key is empty, return an error message
    if (empty($api_key)) {
        return 'API key is missing. Please enter your OpenAI API key.';
    }

    // Set up the endpoint and headers for the API request
    $endpoint = 'https://api.openai.com/v1/models';
    $headers = array(
        'Authorization' => 'Bearer ' . $api_key,
        'Content-Type'  => 'application/json'
    );

    // Make the API request
    $response = wp_remote_get($endpoint, array(
        'headers' => $headers
    ));

    // Check for errors in the response
    if (is_wp_error($response)) {
        return 'Error connecting to OpenAI: ' . $response->get_error_message();
    }

    // Decode the response body
    $body = json_decode(wp_remote_retrieve_body($response), true);

    // Check if the API key is valid based on response status or content
    if (isset($body['error'])) {
        return 'Invalid API key. Please check your API key and try again.';
    } else {
        return 'API key is valid!';
    }
}
