document.addEventListener("DOMContentLoaded", function () {
  const tabs = document.querySelectorAll(".htr-settings-tab");
  const panels = document.querySelectorAll(".htr-settings-panel");

  function activateTab(tab) {
    const target = tab.dataset.tab;

    tabs.forEach(function (item) {
      const active = item === tab;

      item.classList.toggle("is-active", active);
      item.setAttribute("aria-selected", String(active));
      item.tabIndex = active ? 0 : -1;
    });

    panels.forEach(function (panel) {
      const active = panel.dataset.panel === target;

      panel.hidden = !active;
      panel.classList.toggle("is-active", active);
    });
  }

  tabs.forEach(function (tab, index) {
    tab.addEventListener("click", function () {
      activateTab(tab);
    });

    tab.addEventListener("keydown", function (event) {
      let nextIndex = index;

      if (event.key === "ArrowRight") {
        nextIndex = (index + 1) % tabs.length;
      } else if (event.key === "ArrowLeft") {
        nextIndex = (index - 1 + tabs.length) % tabs.length;
      } else if (event.key === "Home") {
        nextIndex = 0;
      } else if (event.key === "End") {
        nextIndex = tabs.length - 1;
      } else {
        return;
      }

      event.preventDefault();
      tabs[nextIndex].focus();
      activateTab(tabs[nextIndex]);
    });
  });

  const resetButton = document.getElementById("htr-reset-settings");
  const settingsForm = document.getElementById("htr-settings-form");

  if (resetButton && settingsForm) {
    resetButton.addEventListener("click", function () {
      if (
        !window.confirm(
          "Are you sure you want to reset all chatbot settings? This will also clear the saved API key.",
        )
      ) {
        return;
      }

      const nonce = settingsForm.querySelector(
        'input[name="htr_reset_settings_nonce"]',
      );

      if (!nonce) {
        window.alert("Unable to reset settings. Please reload the page.");
        return;
      }

      const resetForm = document.createElement("form");
      resetForm.method = "POST";
      resetForm.action = settingsForm.getAttribute("action");

      const fields = {
        action: "htr_reset_settings",
        htr_reset_settings_nonce: nonce.value,
      };

      Object.keys(fields).forEach(function (key) {
        const field = document.createElement("input");
        field.type = "hidden";
        field.name = key;
        field.value = fields[key];
        resetForm.appendChild(field);
      });

      document.body.appendChild(resetForm);
      resetForm.submit();
    });
  }

  /**
   * Send an authenticated admin AJAX request.
   *
   * @param {string} action AJAX action.
   * @returns {Promise<Object>} Parsed response.
   */
  async function sendAdminRequest(action) {
    if (!window.htrAdminSettings) {
      throw new Error("Admin settings are not available.");
    }

    const body = new URLSearchParams({
      action: action,
      nonce: window.htrAdminSettings.nonce,
    });

    const response = await fetch(window.htrAdminSettings.ajaxUrl, {
      method: "POST",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
      },
      body: body.toString(),
    });

    let data;

    try {
      data = await response.json();
    } catch (error) {
      throw new Error("The server returned an invalid response.");
    }

    if (!response.ok || !data) {
      throw new Error("The request could not be completed.");
    }

    return data;
  }

  /**
   * API connection test.
   */
  const testButton = document.getElementById("htr-test-api");
  const testStatus = document.getElementById("htr-test-status");
  const testLog = document.getElementById("htr-test-log");

  if (testButton && testStatus && testLog) {
    testButton.addEventListener("click", async function () {
      if (testButton.disabled) {
        return;
      }

      testButton.disabled = true;
      testStatus.textContent = "Testing connection...";
      testLog.textContent = "Sending test request...";

      try {
        const data = await sendAdminRequest(
          "htr_test_api_connection",
        );

        if (data && data.data && data.data.log) {
          testLog.textContent = JSON.stringify(data.data.log, null, 2);
        } else {
          testLog.textContent = JSON.stringify(data, null, 2);
        }

        testStatus.textContent =
          data && data.success
            ? "Connection successful."
            : "Connection failed.";
      } catch (error) {
        testStatus.textContent = "Test request failed.";

        testLog.textContent = JSON.stringify(
          {
            result: "Failed",
            error: error.message || "Unknown network error.",
          },
          null,
          2,
        );
      } finally {
        testButton.disabled = false;
      }
    });
  }

  /**
   * Fetch frontend widget logs.
   */
  const fetchLogsButton = document.getElementById(
    "htr-fetch-widget-logs",
  );

  const widgetLogsStatus = document.getElementById(
    "htr-widget-logs-status",
  );

  const widgetLogsOutput = document.getElementById(
    "htr-widget-logs-output",
  );

  if (fetchLogsButton && widgetLogsStatus && widgetLogsOutput) {
    fetchLogsButton.addEventListener("click", async function () {
      if (fetchLogsButton.disabled) {
        return;
      }

      fetchLogsButton.disabled = true;
      widgetLogsStatus.textContent = "Fetching widget logs...";

      try {
        const data = await sendAdminRequest(
          "htr_fetch_widget_logs",
        );

        if (!data.success || !data.data || !Array.isArray(data.data.logs)) {
          throw new Error(
            data.data && data.data.message
              ? data.data.message
              : "Unable to retrieve widget logs.",
          );
        }

        const logs = data.data.logs;

        widgetLogsOutput.textContent = logs.length
          ? JSON.stringify(logs, null, 2)
          : "No widget logs are available.";

        widgetLogsStatus.textContent =
          logs.length + " log(s) retrieved successfully.";
      } catch (error) {
        widgetLogsStatus.textContent = "Unable to fetch widget logs.";

        widgetLogsOutput.textContent = JSON.stringify(
          {
            result: "Failed",
            error: error.message || "Unknown network error.",
          },
          null,
          2,
        );
      } finally {
        fetchLogsButton.disabled = false;
      }
    });
  }
});