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
   * Extract a readable error message from a REST API response.
   *
   * @param {Object} data Response data.
   * @param {number} status HTTP status code.
   * @returns {string} Error message.
   */
  function getErrorMessage(data, status) {
    if (data && typeof data.message === "string" && data.message.trim()) {
      return data.message.trim();
    }

    if (
      data &&
      data.data &&
      typeof data.data.message === "string" &&
      data.data.message.trim()
    ) {
      return data.data.message.trim();
    }

    if (status === 429) {
      return "Too many messages. Please wait a moment and try again.";
    }

    if (status >= 500) {
      return "The server encountered an error. Please try again later.";
    }

    return "Unable to process your message. Please try again.";
  }

  /**
   * Send a message to the REST API.
   *
   * @param {string} message Message content.
   * @returns {Promise<Object>} API response data.
   */
  async function sendMessage(message) {
    let response;

    try {
      response = await fetch(window.htrChatWidget.restUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          message: message,
        }),
      });
    } catch (error) {
      throw new Error(
        "Unable to connect to the server. Please check your connection and try again.",
      );
    }

    let data;

    try {
      data = await response.json();
    } catch (error) {
      throw new Error("The server returned an invalid response.");
    }

    if (!response.ok) {
      throw new Error(getErrorMessage(data, response.status));
    }

    if (
      !data ||
      data.success !== true ||
      typeof data.message !== "string" ||
      !data.message.trim()
    ) {
      throw new Error(getErrorMessage(data, response.status));
    }

    return data;
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
      const data = await sendMessage(message);

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
