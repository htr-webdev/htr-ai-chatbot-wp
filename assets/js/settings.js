document.addEventListener("DOMContentLoaded", function () {
  const tabs = document.querySelectorAll(".htr-settings-tab");
  const panels = document.querySelectorAll(".htr-settings-panel");

  function activateTab(tab) {
    const target = tab.dataset.tab;

    tabs.forEach(function (item) {
      const isActive = item === tab;

      item.classList.toggle("is-active", isActive);
      item.setAttribute("aria-selected", String(isActive));
      item.tabIndex = isActive ? 0 : -1;
    });

    panels.forEach(function (panel) {
      const isActive = panel.dataset.panel === target;

      panel.hidden = !isActive;
      panel.classList.toggle("is-active", isActive);
    });
  }

  if (tabs.length && panels.length) {
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
  }

  const resetButton = document.getElementById("htr-reset-settings");
  const settingsForm = document.getElementById("htr-settings-form");

  if (resetButton && settingsForm) {
    resetButton.addEventListener("click", function () {
      const confirmed = window.confirm(
        "Are you sure you want to reset all chatbot settings? This will also clear the saved API key.",
      );

      if (!confirmed) {
        return;
      }

      const nonce = settingsForm.querySelector(
        'input[name="htr_reset_settings_nonce"]',
      );

      if (!nonce) {
        window.alert(
          "Unable to reset settings. Please reload the page and try again.",
        );
        return;
      }

      const resetForm = document.createElement("form");
      resetForm.method = "POST";
      resetForm.action = settingsForm.action;

      const actionInput = document.createElement("input");
      actionInput.type = "hidden";
      actionInput.name = "action";
      actionInput.value = "htr_reset_settings";

      const nonceInput = document.createElement("input");
      nonceInput.type = "hidden";
      nonceInput.name = "htr_reset_settings_nonce";
      nonceInput.value = nonce.value;

      resetForm.appendChild(actionInput);
      resetForm.appendChild(nonceInput);
      document.body.appendChild(resetForm);
      resetForm.submit();
    });
  }
});
