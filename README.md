# HTR AI Chatbot for WordPress

An AI-powered chatbot plugin for WordPress designed to help small businesses automate customer support, answer frequently asked questions, and improve the website experience.

![Version](https://img.shields.io/badge/version-0.1.0-green)
![Status](https://img.shields.io/badge/status-in%20development-yellow)
![License](https://img.shields.io/badge/license-MIT-blue)
![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-blue)
![PHP](https://img.shields.io/badge/PHP-WordPress%20compatible-777BB4)

## Overview

HTR AI Chatbot is a WordPress plugin project focused on bringing AI-powered customer support to WordPress websites.

The planned chatbot will allow website visitors to ask questions through a floating chat widget and receive AI-generated responses. Website administrators will be able to configure API settings, customize chatbot messages, and adjust the widget's appearance and behavior through a modular administration interface.

The project is being developed incrementally, with an emphasis on modular architecture, maintainability, security, and extensibility.

**Current development stage:** The plugin includes its core structure, a modular administration settings interface, settings persistence, input sanitization, and basic administrative security checks. AI provider integration and the complete frontend chatbot experience are still under development.

## Target Users

- Small businesses looking to automate common customer support tasks.
- Service providers who need to answer frequently asked questions.
- Website owners exploring AI-powered customer communication.
- WordPress developers interested in an extensible chatbot plugin.

## Features and Development Roadmap

### Admin Settings Interface

- [x] Initial plugin structure.
- [x] Admin settings page registration.
- [x] Modular settings architecture.
- [x] API configuration interface.
- [x] Chatbot configuration interface.
- [x] Appearance settings interface.
- [x] Behavior settings interface.
- [x] Tabbed settings navigation.
- [x] Admin settings styles.
- [x] Persistent settings storage.
- [x] Settings sanitization and validation.
- [x] Capability checks and nonce verification for settings submissions.
- [x] Display saved settings in the administration interface.
- [x] Preserve the previously saved API key when the API key field is left blank.
- [ ] Settings reset functionality.
- [ ] Complete API key lifecycle and security handling.

### AI Integration

- [ ] AI provider integration.
- [ ] Expandable AI model selection.
- [ ] Functional custom API endpoint support.
- [ ] Server-side API request handling.
- [ ] API error handling and response validation.
- [ ] Secure communication with AI providers.

### Frontend Chat Widget

- [ ] Floating chat launcher.
- [ ] Responsive chat window.
- [ ] Welcome and fallback message handling.
- [ ] Apply configured appearance and position settings.
- [ ] Automatic opening and behavior controls.
- [ ] Frontend-to-backend communication.
- [ ] End-to-end chatbot functionality.

### Knowledge and Conversations

- [ ] Website content as a knowledge source.
- [ ] Custom knowledge base.
- [ ] Conversation history storage.
- [ ] Conversation management and retention controls.
- [ ] Multi-language support.

> Features marked as planned or in development are not yet available as complete, working functionality.

## Technology Stack

| Technology | Purpose |
| --- | --- |
| PHP | Core plugin logic and WordPress integration |
| JavaScript | Admin interactions and planned frontend chat behavior |
| CSS | Administration interface and planned chat widget styling |
| WordPress Hooks API | Plugin initialization and administration integration |
| WordPress Options API | Persistent storage of plugin settings |
| WordPress REST API | Potential communication between the frontend and backend |
| AI APIs | Planned AI-powered response generation |

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
│   │       └── class-settings-saver.php
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

The plugin uses a modular structure to separate administration logic, settings persistence, asset loading, and presentation.

- **`htr-ai-chatbot.php`** — Main plugin entry point.
- **`includes/class-settings.php`** — Coordinates the administration settings modules.
- **`includes/modules/settings/class-settings-menu.php`** — Registers the plugin's administration menu.
- **`includes/modules/settings/class-settings-page.php`** — Renders the settings page and loads its view components.
- **`includes/modules/settings/class-settings-assets.php`** — Handles administration assets.
- **`includes/modules/settings/class-settings-saver.php`** — Processes settings submissions, validates and sanitizes input, and saves configuration.
- **`components/`** — Contains individual PHP view components for the settings interface.
- **`assets/css/settings.css`** — Styles the administration settings interface.
- **`assets/css/chat-style.css`** — Intended to style the frontend chat widget.
- **`assets/js/settings.js`** — Handles settings tab navigation.
- **`assets/js/chat-script.js`** — Intended to manage frontend chat interactions.
- **`includes/class-api-handler.php`** — Intended to handle communication with AI APIs.
- **`includes/class-chat-widget.php`** — Intended to manage frontend chatbot integration.
- **`languages/`** — Contains translation template files.

This architecture keeps the settings interface modular and makes individual sections easier to maintain and extend without placing all presentation markup and settings logic in a single PHP class.

## Requirements

- WordPress 5.8 or later.
- A PHP version compatible with the installed WordPress release and the plugin implementation.
- An AI API account and API key when AI provider integration becomes available.

The minimum supported PHP version will be specified more precisely as compatibility testing progresses.

## Installation

The plugin is under active development and is not yet ready for production use.

For development and testing:

1. Clone or download this repository.
2. Place the plugin directory inside `wp-content/plugins/`.
3. Activate **HTR AI Chatbot** from the WordPress administration dashboard.
4. Open **AI Chatbot** in the administration menu.
5. Review and save the available configuration settings.

The administration interface currently supports saving and restoring configured settings. However, saving configuration does not mean that the AI chatbot itself is operational. AI requests, frontend conversations, and the complete chatbot workflow still require implementation.

**Do not use this development version to handle production customer conversations or sensitive information.**

## Security Considerations

Security is an important part of the development process.

Current and planned security measures include:

- Capability checks for administrative access.
- Input sanitization and validation.
- Context-appropriate output escaping.
- Nonce verification for settings submissions.
- Server-side API requests to avoid exposing API credentials in browser code.
- Appropriate handling of API errors and sensitive conversation data.
- Access controls and retention policies for stored conversations.

The API key field preserves the previously saved key when submitted empty. This behavior does not, by itself, constitute a complete API key security solution.

The presence of individual security measures should not be interpreted as a guarantee that the plugin has undergone a complete security audit.

## Development Status

| Area | Status |
| --- | --- |
| Repository and plugin foundation | Implemented |
| Modular administration architecture | Implemented |
| Settings interface and tab navigation | Implemented |
| Settings persistence | Implemented |
| Settings input sanitization | Implemented |
| Basic administrative security checks | Implemented |
| AI API integration | Planned |
| Functional frontend chatbot | In development |
| Custom knowledge base | Planned |
| Conversation history | Planned |
| Multi-language support | Planned |
| Production release | Planned |

## Contributing

HTR AI Chatbot is a personal project under active development.

Feedback, bug reports, feature suggestions, and code contributions are welcome.

Before submitting a contribution:

1. Check existing issues to avoid duplicate reports.
2. Describe the problem or proposed improvement clearly.
3. Keep changes focused and consistent with the modular architecture.
4. Avoid introducing unnecessary dependencies.
5. Include testing details where applicable.

For substantial changes, consider opening an issue first to discuss the proposed approach.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Author

**Habib Mirzaei**

- GitHub: [htr-webdev](https://github.com/htr-webdev)
- LinkedIn: [Habib Mirzaei](https://www.linkedin.com/in/habib-mirzaei1995/)

---

*HTR AI Chatbot is an independent project and is not affiliated with or endorsed by OpenAI or WordPress.*
