# HTR AI Chatbot for WordPress

An AI-powered WordPress plugin designed to help small businesses automate customer support and improve website communication.

![Version](https://img.shields.io/badge/version-0.1.0-green)
![Status](https://img.shields.io/badge/status-in%20development-yellow)
![License](https://img.shields.io/badge/license-MIT-blue)
![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-blue)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4)

## Overview

HTR AI Chatbot is an independent WordPress plugin project focused on configurable, AI-powered customer support.

The project aims to provide a customizable chatbot with configurable appearance, behavior, and integration with AI providers.

**Current status:** The plugin includes a modular administration settings interface, settings persistence, input validation, administrative security checks, settings reset, and API key management. AI provider integration and the complete frontend chatbot are still under development.

## Features and Roadmap

### Plugin Foundation

- [x] Initial plugin structure and initialization.
- [x] Modular code organization.
- [x] WordPress administration integration.

### Administration Settings

- [x] Settings page and tabbed navigation.
- [x] API, chatbot, appearance, and behavior settings.
- [x] Persistent settings storage.
- [x] Input sanitization and validation.
- [x] Capability checks and nonce verification.
- [x] Settings reset functionality.
- [x] API key storage, replacement, and removal.
- [x] Preserve the saved API key when the input is left blank.
- [x] Clear the saved API key when settings are reset.

### AI Integration

- [ ] AI provider integration.
- [ ] Functional API endpoint and model selection.
- [ ] Server-side API requests.
- [ ] API error handling and response validation.
- [ ] API connectivity testing.

### Frontend Chatbot

- [ ] Floating chat launcher and responsive chat window.
- [ ] Welcome and fallback messages.
- [ ] Apply configured appearance and behavior.
- [ ] Send messages and display AI responses.
- [ ] Loading and error states.

### Knowledge and Conversations

- [ ] Website content as a knowledge source.
- [ ] Custom knowledge base.
- [ ] Conversation history and management.
- [ ] Multi-language support.

## Technology Stack

- **PHP:** Plugin logic and WordPress integration.
- **JavaScript:** Administration interactions and planned chatbot behavior.
- **CSS:** Administration styling and planned chat widget styling.
- **WordPress Options API:** Settings storage.
- **WordPress Hooks API:** Plugin and administration integration.
- **WordPress HTTP API:** Planned communication with AI providers.

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

The codebase separates settings management, administration assets, presentation components, and core plugin functionality to support maintainability and future development.

## Requirements

- WordPress 5.8 or later.
- PHP 7.4 or later.

An AI provider account and API key will be required when provider integration is implemented.

## Installation

The plugin is under active development and is not yet ready for production use.

1. Clone or download this repository.
2. Place the plugin directory in `wp-content/plugins/`.
3. Activate **HTR AI Chatbot** from the WordPress administration dashboard.
4. Open **AI Chatbot** in the administration menu.
5. Configure the available settings and save your changes.

The current settings interface supports saving and resetting configuration. AI-generated responses and the complete frontend chatbot are not yet available.

## Settings and API Key Management

The plugin stores general configuration in the WordPress Options API under `htr_ai_chatbot_settings`.

The API key is stored separately under `htr_ai_chatbot_api_key` with WordPress option autoloading disabled.

Current API key behavior:

- A blank API key field preserves the existing key.
- Entering a new key replaces the saved key.
- The removal option deletes the saved key.
- Resetting plugin settings also deletes the saved key.
- The administration interface displays whether a key is configured without revealing its value.

## Security Considerations

Current security measures include:

- Capability checks for administrative actions.
- Nonce verification for settings submissions and reset requests.
- Input sanitization and validation.
- Output escaping where implemented.
- API key storage separate from general settings.
- Disabled autoloading for the stored API key.
- Protection against direct access to PHP module files.

**Important:** The API key is not encrypted in the database. Separating it from general settings and disabling autoloading do not constitute encryption or a complete security audit. API requests and credential handling will require further security work before production use.

Do not use this development version to handle production customer conversations or sensitive information.

## Contributing

This is a personal project under active development. Bug reports, feedback, and feature suggestions are welcome.

Before contributing:

1. Check existing issues for related work.
2. Describe the proposed change clearly.
3. Keep changes focused and consistent with the modular architecture.
4. Include testing details where applicable.

## License

This project is licensed under the [MIT License](LICENSE).

## Author

**Habib Mirzaei**

- GitHub: [htr-webdev](https://github.com/htr-webdev)
- LinkedIn: [Habib Mirzaei](https://www.linkedin.com/in/habib-mirzaei1995/)
