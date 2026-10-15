<?php
/**
 * Handles communication with the AI API.
 *
 * This class is responsible for sending user messages to the
 * configured AI service and returning the generated responses.
 *
 * @package HTR_AI_Chatbot
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class HTR_AI_Chatbot_API_Handler
 */
class HTR_AI_Chatbot_API_Handler {

    /**
     * Send a message to the AI API and return the response.
     *
     * @param string $message The user's message.
     * @return string The AI-generated response.
     */
    public function send_message( $message ) {
        // Placeholder: API integration will be implemented here.
        return '';
    }
}