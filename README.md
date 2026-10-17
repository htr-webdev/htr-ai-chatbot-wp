# HTR AI Chatbot for WordPress

An AI-powered chatbot plugin for WordPress designed to help small businesses automate customer support, answer frequently asked questions, and provide a better website experience.

![Version](https://img.shields.io/badge/version-0.1.0-green)
![Status](https://img.shields.io/badge/status-in%20development-yellow)
![License](https://img.shields.io/badge/license-MIT-blue)
![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-blue)
![PHP](https://img.shields.io/badge/PHP-WordPress%20compatible-777BB4)

## Overview

HTR AI Chatbot is a WordPress plugin project that aims to bring AI-powered customer support directly to WordPress websites.

The planned chatbot will allow website visitors to ask questions through a floating chat widget and receive AI-generated responses. Website administrators will be able to configure the AI provider, customize chatbot messages, and adjust the widget's appearance and behavior through an administration interface.

The project is being developed incrementally, with an emphasis on modular architecture, maintainability, security, and extensibility.

**Current development stage:** The plugin includes its core structure, chatbot-related classes, a modular admin settings interface, and admin styling. Settings persistence and complete chatbot functionality are still under development.

## Target Users

- Small businesses looking to automate common customer support tasks.
- Service providers who need to answer frequently asked questions.
- Website owners who want to explore AI-powered customer communication.
- WordPress developers interested in an extensible chatbot plugin.

## Features and Development Roadmap

### Admin Settings Interface

- [x] Initial plugin structure.
- [x] Admin settings page registration.
- [x] Modular PHP components for settings sections.
- [x] API configuration interface.
- [x] Chatbot configuration interface.
- [x] Appearance settings interface.
- [x] Behavior settings interface.
- [x] Responsive admin settings styles.
- [ ] Persistent settings storage and validation.
- [ ] Functional save and reset actions.
- [ ] Secure API key management.

### AI Integration

- [ ] AI provider integration.
- [ ] Configurable AI model selection.
- [ ] Custom API endpoint support.
- [ ] Server-side request handling and error management.
- [ ] Secure communication with AI providers.

### Frontend Chat Widget

- [ ] Floating chat launcher.
- [ ] Responsive chat window.
- [ ] Welcome and fallback messages.
- [ ] Configurable widget appearance and position.
- [ ] Automatic opening and behavior controls.
- [ ] Frontend-to-backend communication.

### Knowledge and Conversations

- [ ] Website content as a knowledge source.
- [ ] Custom knowledge base.
- [ ] Conversation history storage.
- [ ] Conversation management and retention controls.
- [ ] Multi-language support.

> Features listed as planned or in development are not yet available as complete, working functionality.

## Technology Stack

| Technology          | Purpose                                                |
| ------------------- | ------------------------------------------------------ |
| PHP                 | Core plugin logic and WordPress integration            |
| JavaScript          | Frontend chatbot interactions                          |
| CSS                 | Chat widget and admin interface styling                |
| WordPress Hooks API | Plugin initialization and admin integration            |
| WordPress REST API  | Planned communication between the frontend and backend |
| AI APIs             | Planned natural language processing                    |

## Project Structure

```text
htr-ai-chatbot-wp/
├── assets/
│   ├── css/
│   │   ├── chat-style.css
│   │   └── settings.css
│   └── js/
│       └── chat-script.js
├── components/
│   ├── settings-header.php
│   ├── api-settings.php
│   ├── chatbot-settings.php
│   ├── appearance-settings.php
│   ├── behavior-settings.php
│   └── settings-actions.php
├── includes/
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

### Architecture

The plugin follows a modular structure to keep its responsibilities separated.

- **`htr-ai-chatbot.php`** — Main plugin entry point.
- **`includes/class-settings.php`** — Registers and renders the administration settings page and loads its stylesheet.
- **`includes/class-api-handler.php`** — Intended to handle communication with AI APIs.
- **`includes/class-chat-widget.php`** — Responsible for frontend chatbot integration.
- **`components/`** — Contains individual PHP view components for the settings interface.
- **`assets/css/settings.css`** — Styles the administration settings page.
- **`assets/css/chat-style.css`** — Styles the frontend chat widget.
- **`assets/js/chat-script.js`** — Intended to manage frontend chat interactions.
- **`languages/`** — Contains translation template files.

The component-based settings interface makes it easier to maintain individual sections and extend the administration panel without placing all presentation markup in a single PHP class.

## Requirements

- WordPress 5.8 or later.
- A PHP version compatible with the installed WordPress release and the plugin's implementation.
- An AI API account and API key once provider integration is implemented.

The minimum supported PHP version will be specified more precisely as development and compatibility testing progress.

## Installation

The plugin is currently under active development and is not yet ready for production use.

For development and testing:

1. Clone or download this repository.
2. Place the plugin directory inside `wp-content/plugins/`.
3. Activate **HTR AI Chatbot** from the WordPress administration dashboard.
4. Open **AI Chatbot** in the administration menu to inspect the settings interface.

Some settings currently serve as presentation-only fields. Saving configuration, making AI requests, and operating the complete frontend chatbot require further implementation.

**Do not use this development version to handle production customer conversations or sensitive information.**

## Security Considerations

Security is a core development priority.

Planned and ongoing security work includes:

- Capability checks for administrative access.
- Input validation and sanitization.
- Context-appropriate output escaping.
- Nonce verification for settings submissions and other privileged actions.
- Server-side API requests to prevent exposing secret API keys in browser code.
- Appropriate handling of API errors and sensitive conversation data.
- Access controls and retention policies for stored conversations.

The presence of individual security measures in the current codebase should not be interpreted as a guarantee that the complete plugin has been security-audited.

## Development Status

| Area                             | Status         |
| -------------------------------- | -------------- |
| Repository and plugin foundation | Implemented    |
| Modular admin settings UI        | Implemented    |
| Admin stylesheet                 | Implemented    |
| Settings persistence             | Planned        |
| AI API integration               | Planned        |
| Functional frontend chatbot      | In development |
| Custom knowledge base            | Planned        |
| Conversation history             | Planned        |
| Multi-language support           | Planned        |
| Production release               | Planned        |

## Contributing

HTR AI Chatbot is a personal project under active development.

Feedback, bug reports, feature suggestions, and code contributions are welcome.

Before submitting a contribution:

1. Check existing issues to avoid duplicate reports.
2. Describe the problem or proposed improvement clearly.
3. Keep changes focused and consistent with the existing modular structure.
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

_HTR AI Chatbot is an independent project and is not affiliated with or endorsed by OpenAI or WordPress._
