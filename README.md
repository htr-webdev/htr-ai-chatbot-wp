# HTR AI Chatbot for WordPress

An AI-powered chatbot plugin for WordPress designed to help small businesses automate customer support and improve website communication.

![Version](https://img.shields.io/badge/version-0.1.0-green)
![Status](https://img.shields.io/badge/status-in%20development-yellow)
![License](https://img.shields.io/badge/license-MIT-blue)
![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-blue)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4)

## Overview

HTR AI Chatbot is an independent WordPress plugin project that aims to bring configurable, AI-powered customer support to WordPress websites.

The planned chatbot experience includes a floating chat widget, customizable messages, configurable appearance and behavior, and integration with an AI provider through server-side API requests.

The plugin is being developed incrementally, with an emphasis on modular architecture, maintainability, security, and extensibility.

**Current development stage:** The plugin includes a modular administration settings interface, settings persistence, input sanitization and validation, administrative security checks, and working settings reset functionality. AI provider integration and the complete frontend chatbot experience are not yet implemented.

## Target Users

- Small businesses looking to automate common customer support tasks.
- Service providers who need to answer frequently asked questions.
- Website owners exploring AI-powered customer communication.
- WordPress developers interested in an extensible chatbot plugin.

## Features and Development Roadmap

### Plugin Foundation

- [x] Initial plugin structure.
- [x] WordPress plugin initialization.
- [x] Modular code organization.
- [x] Basic plugin administration integration.

### Admin Settings Interface

- [x] Admin settings page registration.
- [x] Modular settings architecture.
- [x] API configuration interface.
- [x] Chatbot configuration interface.
- [x] Appearance settings interface.
- [x] Behavior settings interface.
- [x] Tabbed settings navigation.
- [x] Administration interface styling.
- [x] Persistent settings storage.
- [x] Settings sanitization and validation.
- [x] Capability checks and nonce verification for settings submissions.
- [x] Display saved settings in the administration interface.
- [x] Preserve the previously saved API key when the API key field is left blank.
- [x] Reset settings to their default values.
- [x] Verify settings reset functionality in the development environment.

### API Key Management and AI Integration

- [ ] Complete API key lifecycle and security handling.
- [ ] AI provider integration.
- [ ] Expandable AI model selection.
- [ ] Functional custom API endpoint support.
- [ ] Server-side API request handling.
- [ ] API error handling and response validation.
- [ ] Secure communication with AI providers.
- [ ] API connectivity testing from the administration interface.

### Frontend Chat Widget

- [ ] Floating chat launcher.
- [ ] Responsive chat window.
- [ ] Welcome and fallback message handling.
- [ ] Apply configured appearance and position settings.
- [ ] Automatic opening and behavior controls.
- [ ] Frontend-to-backend communication.
- [ ] Message submission and response rendering.
- [ ] Loading, error, and empty states.
- [ ] End-to-end chatbot functionality.

### Knowledge and Conversations

- [ ] Website content as a knowledge source.
- [ ] Custom knowledge base.
- [ ] Conversation history storage.
- [ ] Conversation management and retention controls.
- [ ] Multi-language support.

> Checklist items describe the current implementation status. Planned features are not available until their implementation and testing are complete.

## Technology Stack

| Technology            | Purpose                                                   |
| --------------------- | --------------------------------------------------------- |
| PHP                   | Core plugin logic and WordPress integration               |
| JavaScript            | Administration interactions and planned frontend behavior |
| CSS                   | Administration interface and planned chat widget styling  |
| WordPress Hooks API   | Plugin initialization and administration integration      |
| WordPress Options API | Persistent storage of plugin settings                     |
| WordPress HTTP API    | Planned server-side communication with AI providers       |
| WordPress REST API    | Potential frontend-to-backend communication               |
| AI APIs               | Planned AI-powered response generation                    |

## Project Structure

```text
htr-ai-chatbot-wp/
├── assets/
│   ├── css/
│   │   ├── chat-style.css
│   │   └── settings.css
│   └── js/
│       ├── chat-script.js
│       └── settings.js
├── components/
│   ├── settings-header.php
│   ├── settings-tabs.php
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
│   └── htr-ai-chatbot.pot
├── htr-ai-chatbot.php
├── README.md
├── LICENSE
└── .gitignore
```

## Architecture

The plugin separates administration settings, presentation, asset loading, and request handling into dedicated classes and components.

### Core Files

- **`htr-ai-chatbot.php`** — Defines plugin constants, loads the plugin classes, and initializes the plugin.
- **`includes/class-settings.php`** — Coordinates the administration settings modules.
- **`includes/class-api-handler.php`** — Intended to handle communication with AI APIs.
- **`includes/class-chat-widget.php`** — Handles the frontend chatbot integration structure.

### Settings Modules

- **`includes/modules/settings/class-settings-menu.php`** — Registers the administration menu.
- **`includes/modules/settings/class-settings-page.php`** — Renders the settings page and loads its view components.
- **`includes/modules/settings/class-settings-assets.php`** — Handles administration assets.
- **`includes/modules/settings/class-settings-saver.php`** — Processes settings submissions, sanitizes and validates input, and saves configuration.
- **`includes/modules/settings/class-settings-reset.php`** — Handles resetting plugin settings to their default values.

### Presentation and Assets

- **`components/`** — Contains individual PHP view components for the settings interface.
- **`assets/css/settings.css`** — Styles the administration settings interface.
- **`assets/css/chat-style.css`** — Intended to style the frontend chat widget.
- **`assets/js/settings.js`** — Handles settings tab navigation.
- **`assets/js/chat-script.js`** — Intended to manage frontend chat interactions.
- **`languages/`** — Contains translation template files.

The architecture is designed to keep responsibilities separate and make future development easier without concentrating all functionality in a single class.

## Requirements

- WordPress 5.8 or later.
- PHP 7.4 or later.
- An AI provider account and API key when AI integration becomes available.

Compatibility with specific WordPress and PHP versions should be verified through testing before production use.

## Installation

The plugin is currently under active development and is not yet ready for production use.

For development and testing:

1. Clone or download this repository.
2. Place the plugin directory inside `wp-content/plugins/`.
3. Activate **HTR AI Chatbot** from the WordPress administration dashboard.
4. Open **AI Chatbot** in the administration menu.
5. Configure the available settings and save your changes.
6. Use the reset action to restore settings to their default values when needed.

The administration interface currently supports saving, displaying, and resetting settings. These capabilities do not mean that AI responses or the complete frontend chatbot workflow are operational.

**Do not use this development version to handle production customer conversations or sensitive information.**

## Settings Behavior

The plugin stores its configuration in the WordPress Options API under the `htr_ai_chatbot_settings` option.

The settings interface currently supports:

- API provider, model, endpoint, and API key fields.
- Chatbot name and message configuration.
- Appearance and positioning options.
- Behavior-related options.
- Saving configured values.
- Preserving an existing API key when the API key field is submitted empty.
- Resetting the configuration to predefined default values.

Resetting settings clears the stored API key and restores the other supported settings to their configured defaults.

The reset action requires administrative permission and nonce verification.

## Security Considerations

Security is an ongoing development priority.

Current security measures include:

- Capability checks for administrative settings actions.
- Nonce verification for settings submissions and reset requests.
- Input sanitization and validation.
- Context-appropriate output escaping where implemented.
- Server-side processing of administrative settings.
- Protection against direct access to PHP module files.

Planned security work includes:

- Completing API key storage, replacement, and deletion behavior.
- Keeping API credentials out of browser-side code.
- Secure server-side communication with AI providers.
- API response validation and error handling.
- Access controls and retention policies for conversation data.

The API key field currently preserves the previously stored key when submitted empty. This behavior is a convenience feature and does not constitute a complete API key security solution.

The presence of individual security measures should not be interpreted as a guarantee that the plugin has undergone a complete security audit.

## Development Status

| Area                                  | Status                                |
| ------------------------------------- | ------------------------------------- |
| Plugin foundation                     | Implemented                           |
| Modular administration architecture   | Implemented                           |
| Settings interface and tab navigation | Implemented                           |
| Settings persistence                  | Implemented                           |
| Settings sanitization and validation  | Implemented                           |
| Basic administrative security checks  | Implemented                           |
| Settings reset                        | Implemented and tested in development |
| Complete API key management           | In progress                           |
| AI provider integration               | Planned                               |
| Functional frontend chatbot           | Planned                               |
| Custom knowledge base                 | Planned                               |
| Conversation history                  | Planned                               |
| Multi-language support                | Planned                               |
| Production release                    | Planned                               |

## Contributing

HTR AI Chatbot is a personal project under active development.

Feedback, bug reports, feature suggestions, and code contributions are welcome.

Before submitting a contribution:

1. Check existing issues to avoid duplicate reports.
2. Describe the problem or proposed improvement clearly.
3. Keep changes focused and consistent with the modular architecture.
4. Avoid introducing unnecessary dependencies.
5. Include testing details where applicable.

For substantial changes, consider opening an issue before implementation.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Author

**Habib Mirzaei**

- GitHub: [htr-webdev](https://github.com/htr-webdev)
- LinkedIn: [Habib Mirzaei](https://www.linkedin.com/in/habib-mirzaei1995/)
