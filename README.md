# HTR AI Chatbot for WordPress

An AI-powered WordPress plugin designed to help small businesses automate customer support, answer common questions, and improve website communication.

![Version](https://img.shields.io/badge/version-0.1.0-green)
![Status](https://img.shields.io/badge/status-in%20development-yellow)
![License](https://img.shields.io/badge/license-MIT-blue)
![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-blue)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4)

## Overview

HTR AI Chatbot is an independent WordPress plugin project focused on configurable, AI-powered customer support.

The project aims to provide a customizable chatbot with configurable appearance, behavior, and integration with AI providers. Its modular architecture is intended to keep the codebase maintainable as new features are introduced.

**Current status:** The plugin includes a modular administration settings interface, persistent configuration, input validation, administrative security checks, settings reset, API key management, and server-side requests to OpenAI-compatible Chat Completions endpoints. Endpoint validation, SSRF mitigation, and API response validation are also implemented. The frontend chat interface and the complete message-to-response workflow are still under development.

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
- [x] Display API key configuration status without revealing the saved value.

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
- [ ] API connectivity testing from the administration interface.
- [ ] Connect the frontend messaging workflow to the API handler.

### Frontend Chatbot

- [ ] Floating chat launcher and responsive chat window.
- [ ] Welcome and fallback messages.
- [ ] Apply configured appearance and behavior.
- [ ] Send user messages to the server.
- [ ] Display AI-generated responses.
- [ ] Loading indicators and error states.
- [ ] Conversation input and message validation.

### Knowledge and Conversations

- [ ] Use website content as a knowledge source.
- [ ] Custom knowledge base.
- [ ] Conversation history and management.
- [ ] Multi-language support.

## Technology Stack

- **PHP:** Plugin logic and server-side API integration.
- **JavaScript:** Administration interactions and planned chatbot behavior.
- **CSS:** Administration styling and planned chat widget styling.
- **WordPress Options API:** Persistent settings and API key storage.
- **WordPress Hooks API:** Plugin initialization and administration integration.
- **WordPress HTTP API:** Server-side communication with OpenAI-compatible endpoints.
- **Git and GitHub:** Version control, issue tracking, and collaborative development.

## Project Structure

```text
htr-ai-chatbot-wp/
├── assets/
│   ├── css/
│   └── js/
├── components/
│   ├── api-settings.php
│   ├── chatbot-settings.php
│   ├── appearance-settings.php
│   ├── behavior-settings.php
│   └── settings-actions.php
├── includes/
│   ├── modules/
│   │   └── settings/
│   │       ├── class-settings-assets.php
│   │       ├── class-settings-menu.php
│   │       ├── class-settings-page.php
│   │       ├── class-settings-saver.php
│   │       └── class-settings-reset.php
│   ├── class-api-handler.php
│   ├── class-chat-widget.php
│   └── class-settings.php
├── languages/
├── htr-ai-chatbot.php
├── README.md
├── LICENSE
└── .gitignore
```

The project separates settings management, administration assets, presentation components, and core plugin functionality. This structure supports maintainability and allows future features to be developed in focused modules.

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

The administration interface currently supports saving and resetting configuration. The server-side API handler is implemented, but the complete frontend chatbot and end-to-end user messaging workflow are not yet available.

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

The API handler is a server-side component. Its implementation alone does not mean that the frontend chatbot is ready or that visitors can already exchange messages with an AI provider through the website.

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

**Important:** The API key is not encrypted in the database. Separating it from general settings and disabling autoloading do not constitute encryption or a complete security audit.

Before production use, the plugin requires further testing, including end-to-end API integration, frontend request security, error handling, and deployment-specific security review.

Do not use this development version to handle production customer conversations or sensitive information.

## Development Status

Development is ongoing. The current focus is on completing the server-side integration and building the frontend messaging workflow.

Planned areas of development include:

1. Connecting frontend messages to the server-side API handler.
2. Building the interactive chat interface.
3. Adding API connectivity testing in the administration area.
4. Improving conversation handling and user-facing error messages.
5. Developing website content integration and knowledge management.
6. Expanding automated and manual testing before production release.

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
