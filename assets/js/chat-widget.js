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
  const sendButton = widget.querySelector(".htr-chat-widget__send");
  const messages = widget.querySelector(".htr-chat-widget__messages");

  if (
    !launcher ||
    !chatWindow ||
    !closeButton ||
    !form ||
    !input ||
    !sendButton ||
    !messages ||
    !window.htrChatWidget ||
    !window.htrChatWidget.restUrl
  ) {
    return;
  }

  let isSending = false;
  let lastFailedMessage = null;

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
   * @returns {HTMLElement} Created message element.
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

    return message;
  }

  /**
   * Handle message submission.
   *
   * @param {Event} event Form submission event.
   */
  async function handleSubmit(event) {
    event.preventDefault();

    if (isSending) {
      return;
    }

    const message = input.value.trim();

    if (!message) {
      return;
    }

    isSending = true;
    sendButton.disabled = true;
    input.disabled = true;

    if (message !== lastFailedMessage) {
      addMessage(message, "visitor");
    }

    const loadingMessage = addMessage("Thinking...", "assistant");

    try {
      const response = await fetch(window.htrChatWidget.restUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          message: message,
        }),
      });

      let data;

      try {
        data = await response.json();
      } catch (error) {
        throw new Error("The server returned an invalid response.");
      }

      if (!response.ok || !data.success || typeof data.message !== "string") {
        throw new Error(
          typeof data.message === "string"
            ? data.message
            : "Unable to process your message. Please try again.",
        );
      }

      loadingMessage.remove();

      addMessage(data.message, "assistant");

      input.value = "";
      lastFailedMessage = null;
    } catch (error) {
      loadingMessage.remove();

      addMessage(
        error.message || "Something went wrong. Please try again.",
        "assistant",
      );

      lastFailedMessage = message;
    } finally {
      isSending = false;
      sendButton.disabled = false;
      input.disabled = false;
      input.focus();
    }
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
