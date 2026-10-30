document.addEventListener("DOMContentLoaded", function () {
  const widget = document.querySelector(".htr-chat-widget");

  if (!widget) {
    return;
  }

  const launcher = widget.querySelector(".htr-chat-widget__launcher");
  const chatWindow = widget.querySelector(".htr-chat-widget__window");
  const closeButton = widget.querySelector(".htr-chat-widget__close");
  const form = widget.querySelector(".htr-chat-widget__form");
  const input = widget.querySelector(".htr-chat-widget__input");
  const messages = widget.querySelector(".htr-chat-widget__messages");

  if (
    !launcher ||
    !chatWindow ||
    !closeButton ||
    !form ||
    !input ||
    !messages
  ) {
    return;
  }

  /**
   * Open the chat window.
   */
  function openChat() {
    chatWindow.hidden = false;
    chatWindow.setAttribute("aria-hidden", "false");
    launcher.setAttribute("aria-expanded", "true");

    input.focus();
  }

  /**
   * Close the chat window.
   */
  function closeChat() {
    chatWindow.hidden = true;
    chatWindow.setAttribute("aria-hidden", "true");
    launcher.setAttribute("aria-expanded", "false");

    launcher.focus();
  }

  /**
   * Add a message to the chat.
   *
   * @param {string} text Message content.
   * @param {string} type Message type.
   */
  function addMessage(text, type) {
    const message = document.createElement("div");
    const paragraph = document.createElement("p");

    message.classList.add(
      "htr-chat-widget__message",
      "htr-chat-widget__message--" + type,
    );

    paragraph.textContent = text;

    message.appendChild(paragraph);
    messages.appendChild(message);

    messages.scrollTop = messages.scrollHeight;
  }

  /**
   * Handle message submission.
   *
   * @param {Event} event Form submission event.
   */
  function handleSubmit(event) {
    event.preventDefault();

    const message = input.value.trim();

    if (!message) {
      return;
    }

    addMessage(message, "visitor");

    input.value = "";
    input.focus();
  }

  launcher.addEventListener("click", openChat);
  closeButton.addEventListener("click", closeChat);

  form.addEventListener("submit", handleSubmit);

  /**
   * Submit the message with Enter.
   * Shift + Enter inserts a new line.
   */
  input.addEventListener("keydown", function (event) {
    if (event.key === "Enter" && !event.shiftKey) {
      event.preventDefault();
      form.requestSubmit();
    }
  });

  /**
   * Close the chat with Escape.
   */
  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape" && !chatWindow.hidden) {
      closeChat();
    }
  });
});
