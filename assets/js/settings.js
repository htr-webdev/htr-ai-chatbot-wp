document.addEventListener("DOMContentLoaded", function () {
  const tabs = document.querySelectorAll(".htr-settings-tab");
  const panels = document.querySelectorAll(".htr-settings-panel");

  if (!tabs.length || !panels.length) {
    return;
  }

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
});
