# HTR AI Chatbot for WordPress

An AI-powered chatbot plugin for WordPress that helps small businesses automate customer support, answer common questions, and improve website communication.

![Version](https://img.shields.io/badge/version-0.1.0-green)
![Status](https://img.shields.io/badge/status-in%20development-yellow)
![License](https://img.shields.io/badge/license-MIT-blue)
![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-blue)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4)

## Overview

HTR AI Chatbot is an independent WordPress plugin project focused on providing configurable, AI-powered customer support for small businesses.

The plugin uses a modular architecture that separates administration settings, frontend presentation, REST API handling, AI provider communication, and diagnostic logging.

The current implementation includes a configurable administration interface, secure API key management, server-side AI API integration, a public chat REST endpoint, and a frontend chat widget connected to the AI service.

The project is under active development. Advanced capabilities such as website content integration, a custom knowledge base, and conversation management are planned for future development.

## Features

### Plugin Foundation

- [x] Initial plugin structure and initialization.
- [x] Modular code organization.
- [x] WordPress administration integration.
- [x] Protection against direct access to PHP module files.
- [x] WordPress hooks and asset management.

### Administration Settings

- [x] Tabbed settings interface.
- [x] Separate API, chatbot, appearance, and behavior settings.
- [x] Persistent settings storage.
- [x] Input sanitization and validation.
- [x] Capability checks and nonce verification.
- [x] Settings reset functionality with confirmation.
- [x] API key storage, replacement, and removal.
- [x] Preserve the saved API key when the input is left blank.
- [x] Display API key configuration status without revealing its value.

### AI API Integration

- [x] Server-side API request handling.
- [x] OpenAI-compatible Chat Completions format.
- [x] Support for OpenAI and compatible API providers.
- [x] Configurable API endpoint and model.
- [x] Server-side API key retrieval and Bearer authentication.
- [x] WordPress HTTP API for outbound requests.
- [x] API endpoint validation and SSRF mitigation.
- [x] HTTP status and response structure validation.
- [x] Request timeouts and response-size restrictions.
- [x] Redirects disabled for outbound API requests.
- [x] Safe handling of unsuccessful requests and malformed responses.

### Frontend Chat Widget

- [x] Floating chat launcher.
- [x] Expandable and closable chat window.
- [x] Welcome message.
- [x] Visitor and assistant message styling.
- [x] Responsive chat interface.
- [x] Keyboard interactions (Enter, Shift + Enter, and Escape).
- [x] Basic accessibility attributes.
- [x] Send visitor messages to the server.
- [x] Receive and display AI-generated responses.
- [x] Loading indicator during API requests.
- [x] User-friendly error messages.
- [x] Retry after unsuccessful requests.
- [x] Input validation.
- [x] Prevention of duplicate submissions.
- [x] Sequential message and response rendering.

### Chat REST API

- [x] Public WordPress REST API endpoint.
- [x] JSON request handling.
- [x] Message validation and sanitization.
- [x] Configurable message length validation.
- [x] Request body size restrictions.
- [x] IP-based rate limiting.
- [x] Server-side AI API integration.
- [x] Generic error responses that avoid exposing internal API details.

### Diagnostics and Logging

- [x] Frontend widget request logging.
- [x] Store recent visitor and assistant message events.
- [x] Record request status and diagnostic information.
- [x] Store a limited number of recent log entries.
- [x] Administration interface for viewing widget logs.
- [x] Fetch and refresh diagnostic logs.
- [x] Restrict log access to authorized administrators.

### Planned Features

- [ ] Apply all configured appearance settings to the frontend widget.
- [ ] Apply configured chatbot behavior settings.
- [ ] Website content integration.
- [ ] Custom knowledge base management.
- [ ] Conversation history and management.
- [ ] Multi-language support.
- [ ] Expanded automated testing and production-readiness improvements.

## Technology Stack

- **PHP:** Plugin logic and server-side API integration.
- **JavaScript:** Frontend chat interactions and administration behavior.
- **CSS:** Frontend widget and administration styling.
- **WordPress Options API:** Persistent plugin configuration.
- **WordPress Hooks API:** Plugin initialization and WordPress integration.
- **WordPress REST API:** Chat message endpoint.
- **WordPress HTTP API:** Server-side communication with compatible AI services.
- **WordPress Transients API:** Temporary rate-limit data.
- **Git and GitHub:** Version control, issue tracking, and project management.

## Architecture

The plugin follows a modular structure to keep its main responsibilities separated.

The main components include:

- **Settings Module:** Handles administration settings, navigation, asset loading, saving, and resetting.
- **API Handler:** Manages server-side communication with configured AI API endpoints.
- **Chat REST Controller:** Validates incoming messages and connects the frontend widget to the API handler.
- **Chat Widget:** Renders the frontend interface and loads its assets.
- **Widget Logger:** Records recent chat activity and diagnostic information for administration use.
- **Components:** Contains reusable presentation components for the administration interface.

This separation makes the codebase easier to maintain, test, and extend as new features are introduced.

## Project Structure

```text
htr-ai-chatbot-wp/
├── assets/
│   ├── css/
│   └── js/
├── components/
├── includes/
│   ├── modules/
│   │   └── settings/
│   ├── class-api-handler.php
│   ├── class-chat-rest-controller.php
│   ├── class-chat-widget.php
│   └── class-settings.php
├── languages/
├── .gitattributes
├── .gitignore
├── LICENSE
├── README.md
└── htr-ai-chatbot.php
```

The structure above provides a high-level overview. Additional files and modules may be introduced as development progresses.

## Requirements

- WordPress 5.8 or later.
- PHP 7.4 or later.
- An API key from a compatible AI service.
- An HTTPS endpoint compatible with the OpenAI Chat Completions request format.

The configured API endpoint must pass the plugin's URL validation rules. These restrictions are intended to reduce SSRF risks but do not replace network-level security controls.

## Installation

The plugin is currently under active development and is not recommended for production use.

1. Clone or download this repository.
2. Place the plugin directory in `wp-content/plugins/`.
3. Activate **HTR AI Chatbot** from the WordPress administration dashboard.
4. Open **AI Chatbot** in the administration menu.
5. Configure the available API and chatbot settings.
6. Enter your API key and configure a compatible endpoint.
7. Save the settings.
8. Open a frontend page to test the chat widget.

A compatible API service and valid API credentials are required for AI-generated responses.

## Configuration and API Key Management

The plugin stores general configuration in the WordPress Options API under:

```text
htr_ai_chatbot_settings
```

The API key is stored separately under:

```text
htr_ai_chatbot_api_key
```

The API key option is configured with autoloading disabled.

Current API key behavior:

- Leaving the API key field blank preserves the existing key.
- Entering a new key replaces the saved key.
- Using the removal option deletes the saved key.
- Resetting plugin settings also removes the saved API key.
- The administration interface displays whether a key is configured without revealing its value.

The API key is retrieved and used exclusively by server-side PHP code. It is not included in frontend JavaScript or visitor chat request payloads.

## AI API Integration

The plugin communicates with OpenAI-compatible Chat Completions endpoints through the WordPress HTTP API.

The API handler is responsible for:

- Reading the configured provider, endpoint, and model.
- Retrieving the stored API key.
- Building the API request payload.
- Sending authenticated server-side HTTP requests.
- Processing successful API responses.
- Validating HTTP status codes and response structure.
- Handling connection errors and unsuccessful responses safely.

The implementation supports configurable endpoints, allowing compatible third-party AI services to be used without exposing credentials to the browser.

### Endpoint Security

Since the API endpoint is configurable, URL validation is an important part of the request workflow.

The implementation includes:

- HTTPS endpoint validation.
- Restrictions on unsafe destinations and internal hostnames.
- Port validation.
- WordPress safe HTTP request handling.
- Disabled redirects.
- Request timeouts.
- Response-size restrictions.

These measures provide defense-in-depth against server-side request forgery (SSRF). They do not guarantee protection against every possible DNS, hosting, or network-level attack scenario.

## Chat REST API

The plugin exposes a public REST API endpoint for receiving messages from website visitors.

**Endpoint:**

```http
POST /wp-json/htr-ai-chatbot/v1/chat
```

**Request body:**

```json
{
  "message": "Hello, how can you help me?"
}
```

**Successful response:**

```json
{
  "success": true,
  "message": "Hello! How can I help you today?"
}
```

The endpoint is designed for public website visitors, so authentication is not required for ordinary chat requests.

Security and validation measures include:

- Request method handling.
- JSON payload validation.
- Message sanitization.
- Empty message rejection.
- Message length validation.
- Request body size restrictions.
- IP-based rate limiting.
- Server-side API communication.
- Generic error responses.

The endpoint does not accept or require the AI API key from the browser.

## Frontend Chat Widget

The frontend widget is implemented using native JavaScript and CSS without requiring a third-party frontend framework.

Its current functionality includes:

- Floating launcher and expandable chat window.
- Welcome message.
- Visitor and assistant message rendering.
- Responsive layout.
- Message submission through the REST API.
- Display of AI-generated responses.
- Loading state during pending requests.
- User-friendly error handling.
- Retry after failed requests.
- Prevention of duplicate submissions.
- Input validation.
- Sequential rendering of messages and responses.

The widget currently handles messages within the active browser session. Persistent conversation history and conversation management are not implemented.

## Diagnostics and Widget Logs

The plugin includes a diagnostic logging component for monitoring recent frontend chat activity.

Administrators can use the settings interface to inspect recent widget events and request results.

Logged information can include:

- Event type.
- Request status.
- Configured provider and model information.
- HTTP response status.
- Diagnostic error codes.
- Timestamps.

The logger keeps a limited number of recent entries rather than maintaining a permanent conversation database.

Diagnostic logs are intended for development and troubleshooting. They should not be treated as a complete audit trail or persistent conversation history.

## Security Considerations

Security is considered throughout the plugin architecture.

Current measures include:

- WordPress capability checks for administrative actions.
- Nonce verification for administrative requests.
- Input sanitization and validation.
- Output escaping where applicable.
- Separate storage for the API key.
- Disabled autoloading for the API key option.
- Protection against direct access to PHP files.
- Server-side API key usage.
- Safe outbound HTTP requests.
- API endpoint validation and SSRF mitigation.
- Request timeout and response-size restrictions.
- Disabled redirects for API requests.
- Public endpoint input validation.
- IP-based rate limiting.
- Generic error responses.

**Important security limitations:**

The API key is not encrypted in the WordPress database. Separating it from general settings and disabling autoloading do not constitute encryption.

The public chat endpoint is intentionally accessible without WordPress authentication. Rate limiting and request validation provide basic safeguards but do not replace a dedicated abuse-prevention strategy.

The plugin has not undergone a formal security audit. Hosting configuration, network restrictions, and deployment practices also affect its overall security.

Do not use this development version to process sensitive customer information or critical production conversations.

## Development Status and Roadmap

Development is organized around focused GitHub issues and pull requests.

The current implementation covers:

1. Plugin foundation and modular architecture.
2. Administration settings and configuration management.
3. Secure API key management.
4. Server-side AI API communication.
5. Public chat REST API.
6. Frontend chat widget.
7. Frontend-to-API integration.
8. Basic diagnostic logging.

Future development will focus on:

1. Completing appearance and behavior settings integration.
2. Improving automated and manual test coverage.
3. Integrating website content as a knowledge source.
4. Implementing a custom knowledge base.
5. Adding conversation history and management.
6. Expanding language support.
7. Improving production readiness.

Features marked as planned are not currently available.

## Contributing

This is a personal project under active development. Bug reports, feedback, and feature suggestions are welcome.

Before contributing:

1. Check existing GitHub issues for related work.
2. Describe the proposed change clearly.
3. Keep changes focused and consistent with the modular architecture.
4. Follow WordPress coding and security practices.
5. Test the affected functionality.
6. Include relevant testing details in pull requests.

## License

This project is licensed under the [MIT License](LICENSE).

## Author

**Habib Mirzaei**

- GitHub: [htr-webdev](https://github.com/htr-webdev)
- LinkedIn: [Habib Mirzaei](https://www.linkedin.com/in/habib-mirzaei1995/)
