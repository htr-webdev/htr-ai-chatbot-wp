# HTR AI Chatbot for WordPress

An AI-powered WordPress plugin designed to help small businesses automate customer support, answer common questions, and improve website communication.

![Version](https://img.shields.io/badge/version-0.1.0-green)
![Status](https://img.shields.io/badge/status-in%20development-yellow)
![License](https://img.shields.io/badge/license-MIT-blue)
![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-blue)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4)

## Overview

HTR AI Chatbot is an independent WordPress plugin project focused on configurable, AI-powered customer support.

The plugin is being developed with a modular architecture that separates administration settings, frontend presentation, REST API handling, and AI provider communication.

**Current status:** The plugin includes a modular administration settings interface, persistent configuration, secure API key management, server-side API request handling, endpoint validation, SSRF mitigation, and a public chat REST endpoint. The initial frontend chat widget is also implemented, including its launcher, responsive chat window, welcome message, and local message interactions. Connecting frontend messages to the AI API is still under development.

## Features and Roadmap

### Plugin Foundation

- [x] Initial plugin structure and initialization.
- [x] Modular code organization.
- [x] WordPress administration integration.
- [x] Protection against direct access to PHP module files.

### Administration Settings

- [x] Settings page with tabbed navigation.
- [x] Separate API, chatbot, appearance, and behavior settings.
- [x] Persistent settings storage.
- [x] Input sanitization and validation.
- [x] Capability checks and nonce verification.
- [x] Settings reset functionality.
- [x] API key storage, replacement, and removal.
- [x] Preserve the saved API key when the input is left blank.
- [x] Clear the saved API key when settings are reset.
- [x] Display API key configuration status without revealing its value.

### AI Integration

- [x] Server-side API request handling.
- [x] OpenAI-compatible Chat Completions request format.
- [x] Support for a configurable API endpoint.
- [x] Read the configured API model and provider settings.
- [x] Retrieve the API key from separate WordPress option storage.
- [x] Use the WordPress HTTP API for outbound requests.
- [x] Validate API endpoint URLs and apply SSRF mitigation measures.
- [x] Validate HTTP status codes and API response structure.
- [x] Apply request timeouts, disable redirects, and limit response size.
- [x] Handle unsuccessful requests and malformed responses safely.
- [x] Public REST API endpoint for chat messages.
- [x] Basic request validation and IP-based rate limiting.
- [ ] Connect the frontend messaging workflow to the REST API.
- [ ] API connectivity testing from the administration interface.

### Frontend Chat Widget

- [x] Floating chat launcher.
- [x] Open and close chat window.
- [x] Welcome message.
- [x] Separate visitor and assistant message styles.
- [x] Local visitor message rendering.
- [x] Responsive chat window layout.
- [x] Keyboard interactions (Enter, Shift + Enter, and Escape).
- [x] Basic accessibility attributes.
- [ ] Send user messages to the server.
- [ ] Display AI-generated responses.
- [ ] Loading indicators and error states.
- [ ] Apply configured appearance and behavior settings.
- [ ] Server-side message validation and response handling.

### Knowledge and Conversations

- [ ] Use website content as a knowledge source.
- [ ] Custom knowledge base.
- [ ] Conversation history and management.
- [ ] Multi-language support.

## Technology Stack

- **PHP:** Plugin logic and server-side API integration.
- **JavaScript:** Frontend chat interactions and administration behavior.
- **CSS:** Frontend widget and administration styling.
- **WordPress Options API:** Persistent settings and API key storage.
- **WordPress Hooks API:** Plugin initialization and WordPress integration.
- **WordPress REST API:** Chat message endpoint.
- **WordPress HTTP API:** Server-side communication with OpenAI-compatible endpoints.
- **Git and GitHub:** Version control, issue tracking, and project management.

## Project Structure

```text
htr-ai-chatbot-wp/
├── assets/
│   ├── css/
│   │   ├── chat-style.css
│   │   ├── chat-widget.css
│   │   └── settings.css
│   └── js/
│       ├── chat-script.js
│       ├── chat-widget.js
│       └── settings.js
├── components/
│   ├── api-settings.php
│   ├── appearance-settings.php
│   ├── behavior-settings.php
│   ├── chatbot-settings.php
│   ├── settings-actions.php
│   ├── settings-header.php
│   └── settings-tabs.php
├── includes/
│   ├── modules/
│   │   └── settings/
│   │       ├── class-settings-assets.php
│   │       ├── class-settings-menu.php
│   │       ├── class-settings-page.php
│   │       ├── class-settings-reset.php
│   │       └── class-settings-saver.php
│   ├── class-api-handler.php
│   ├── class-chat-rest-controller.php
│   ├── class-chat-widget.php
│   └── class-settings.php
├── languages/
│   └── htr-ai-chatbot.pot
├── .gitattributes
├── .gitignore
├── LICENSE
├── README.md
└── htr-ai-chatbot.php
```

### Main Directories and Files

- **assets/css/** — Stylesheets for the administration interface and frontend chat widget.
- **assets/js/** — JavaScript files for administration interactions and frontend chat behavior.
- **components/** — Reusable PHP presentation components for the administration settings page.
- **includes/modules/settings/** — Modular classes responsible for settings assets, menu registration, page rendering, saving, and resetting.
- **includes/class-api-handler.php** — Handles server-side communication with compatible AI API endpoints.
- **includes/class-chat-rest-controller.php** — Registers and handles the public chat REST API endpoint.
- **includes/class-chat-widget.php** — Enqueues frontend assets and renders the chat widget.
- **includes/class-settings.php** — Initializes the administration settings functionality.
- **languages/** — Translation template files.
- **htr-ai-chatbot.php** — Main plugin file, constants, class loading, and initialization.

**Frontend asset note:** `chat-widget.css` and `chat-widget.js` are the active frontend widget assets currently enqueued by the chat widget class. `chat-style.css` and `chat-script.js` are currently placeholder files.

## Requirements

- WordPress 5.8 or later.
- PHP 7.4 or later.
- An API key from a compatible AI service to make authenticated API requests.
- An HTTPS endpoint compatible with the supported Chat Completions request format.

The configured endpoint must pass the plugin's URL validation rules. These restrictions are intended to reduce the risk of server-side request forgery (SSRF), but they do not replace network-level security controls.

## Installation

The plugin is under active development and is not yet ready for production use.

1. Clone or download this repository.
2. Place the plugin directory in `wp-content/plugins/`.
3. Activate **HTR AI Chatbot** from the WordPress administration dashboard.
4. Open **AI Chatbot** in the administration menu.
5. Configure the available settings.
6. Enter an API key and configure a compatible endpoint if you intend to test server-side API requests.
7. Save your settings.

The administration interface, server-side API handler, REST endpoint, and initial frontend chat interface are implemented. However, the complete frontend-to-AI messaging workflow is not yet available.

## Settings and API Key Management

The plugin stores general configuration in the WordPress Options API under `htr_ai_chatbot_settings`.

The API key is stored separately under `htr_ai_chatbot_api_key`, with WordPress option autoloading disabled.

Current API key behavior:

- Leaving the API key field blank preserves the existing key.
- Entering a new key replaces the saved key.
- Using the removal option deletes the saved key.
- Resetting plugin settings also deletes the saved key.
- The administration interface displays whether a key is configured without revealing its value.

The API handler reads the saved configuration and uses the API key to authenticate server-side requests. The key is not intended to be exposed to frontend JavaScript.

## Server-Side API Integration

The plugin uses the WordPress HTTP API to send requests to configured OpenAI-compatible Chat Completions endpoints.

The current implementation includes:

- Server-side HTTP requests using `wp_safe_remote_post()`.
- Bearer-token authentication.
- JSON request payloads containing the configured model and user message.
- HTTPS endpoint validation and restrictions on unsafe destinations.
- Redirects disabled for API requests.
- A request timeout and response-size limit.
- HTTP status and response structure validation.
- Safe handling of failed requests and unexpected API responses.

The endpoint validation is designed to reduce SSRF risks by rejecting unsafe URL configurations, including local or internal hostnames and unsupported ports. WordPress HTTP URL validation provides an additional check.

These measures are defense-in-depth safeguards, not a guarantee against every possible SSRF scenario. DNS behavior, hosting configuration, and network-level access controls can affect the overall security of outbound requests.

## Chat REST API

The plugin provides a public REST endpoint for receiving visitor messages.

**Endpoint:**

```text
POST /wp-json/htr-ai-chatbot/v1/chat
```

The endpoint currently includes:

- Request method validation.
- Message input validation and sanitization.
- Configurable message length validation.
- IP-based rate limiting.
- Server-side API handler integration.
- Generic error responses that avoid exposing internal API details.

The endpoint is designed for public website visitors, so authentication is not required for ordinary chat requests. Request validation and rate limiting provide basic protection against misuse.

The frontend widget is not yet connected to this endpoint. That integration is planned as a separate development task.

## Frontend Chat Widget

The initial frontend chat interface is implemented using native JavaScript and CSS without third-party frontend dependencies.

Current functionality includes:

- A floating chat launcher.
- An expandable chat window.
- A welcome message.
- Separate visual styles for visitor and assistant messages.
- Local rendering of visitor messages.
- Message submission using Enter.
- Support for line breaks using Shift + Enter.
- Closing the chat window using Escape.
- Responsive layout for smaller screens.

At this stage, messages are handled locally in the browser. The widget does not yet send messages to the REST endpoint or display actual AI-generated responses.

Loading indicators, server-side error handling, and integration with the administration appearance settings are planned for future development.

## Security Considerations

Current security measures include:

- Capability checks for administrative actions.
- Nonce verification for settings submissions and reset requests.
- Input sanitization and validation.
- Output escaping where implemented.
- API key storage separate from general settings.
- Disabled autoloading for the stored API key.
- Protection against direct access to PHP module files.
- Safe WordPress HTTP requests for outbound API communication.
- API endpoint validation and SSRF mitigation.
- Request timeout, disabled redirects, and response-size restrictions.
- Validation of API response status and structure.
- Basic rate limiting for public chat requests.

**Important:** The API key is not encrypted in the database. Separating it from general settings and disabling autoloading do not constitute encryption or a complete security audit.

Before production use, the plugin requires further testing, including end-to-end API integration, frontend request security, error handling, and deployment-specific security review.

Do not use this development version to handle production customer conversations or sensitive information.

## Development Status

Development is ongoing. The current implementation includes the plugin foundation, administration settings, server-side API handling, a public chat endpoint, and the initial frontend chat interface.

The current development focus is completing the connection between the frontend widget and the server-side messaging workflow.

Planned development areas include:

1. Connecting frontend messages to the REST API.
2. Displaying AI-generated responses in the chat window.
3. Adding loading indicators and user-facing error messages.
4. Applying saved appearance and behavior settings to the frontend widget.
5. Adding API connectivity testing in the administration area.
6. Developing website content integration and knowledge management.
7. Expanding automated and manual testing before production release.

Features marked as incomplete in the roadmap should not be considered available.

## Contributing

This is a personal project under active development. Bug reports, feedback, and feature suggestions are welcome.

Before contributing:

1. Check existing issues for related work.
2. Describe the proposed change clearly.
3. Keep changes focused and consistent with the modular architecture.
4. Test the affected functionality.
5. Include relevant testing details in pull requests.

## License

This project is licensed under the [MIT License](LICENSE).

## Author

**Habib Mirzaei**

- GitHub: [htr-webdev](https://github.com/htr-webdev)
- LinkedIn: [Habib Mirzaei](https://www.linkedin.com/in/habib-mirzaei1995/)
