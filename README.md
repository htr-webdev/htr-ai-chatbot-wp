# htr-ai-chatbot-wp

An AI-powered chatbot plugin for WordPress that helps small businesses automate customer support and answer common questions — without hiring a support team.

![Version](https://img.shields.io/badge/version-0.1.0-green)
![Status](https://img.shields.io/badge/status-in%20development-yellow)
![License](https://img.shields.io/badge/license-MIT-blue)
![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-blue)

## Overview

This plugin adds a smart chat widget to any WordPress site. The chatbot uses an AI API to understand user questions and respond based on the site's content and custom knowledge base.

**Target users:** Small businesses, online stores, and service providers who want to reduce support workload.

## Features

- [ ] Chat widget on the frontend (floating button + chat window)
- [ ] AI API integration for natural language responses
- [ ] Admin settings page for API key and customization
- [ ] Custom knowledge base (train the bot on your own content)
- [ ] Conversation history storage
- [ ] Multi-language support

> This project is currently in active development. Features will be added incrementally.

## Tech Stack

- **PHP** — WordPress plugin core
- **JavaScript** — Chat widget frontend
- **CSS** — Widget styling
- **AI API** — Natural language processing
- **WordPress REST API** — Communication between frontend and backend

## Project Structure

```text
htr-ai-chatbot-wp/
├── htr-ai-chatbot.php        # Main plugin file
├── includes/
│   ├── class-api-handler.php  # AI API communication
│   ├── class-chat-widget.php  # Chat widget logic
│   └── class-settings.php     # Admin settings page
├── assets/
│   ├── css/
│   │   └── chat-style.css
│   └── js/
│       └── chat-script.js
├── languages/
│   └── htr-ai-chatbot.pot
├── README.md
├── LICENSE
└── .gitignore
```

## Installation

> Instructions will be added once the first working version is released.

## Development Status

| Phase                  | Status      |
| ---------------------- | ----------- |
| Project setup          | Done        |
| Core plugin structure  | In progress |
| AI API integration     | Planned     |
| Chat widget (frontend) | Planned     |
| Admin settings page    | Planned     |
| First release          | Planned     |

## Contributing

This is a personal project under active development. Suggestions and feedback are welcome — feel free to open an issue.

## License

This project is licensed under the MIT License — see the [LICENSE](LICENSE) file for details.

## Author

**Habib Mirzaei**

- GitHub: https://github.com/htr-webdev
- LinkedIn: https://www.linkedin.com/in/habib-mirzaei1995/
