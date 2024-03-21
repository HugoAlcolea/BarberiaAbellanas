// /* index.js */

document.addEventListener("DOMContentLoaded", function() {
    const tabs = document.querySelectorAll(".nav-link");
    const tabContents = document.querySelectorAll(".tabcontent");

    function hideTabs() {
        tabContents.forEach(tabContent => {
            tabContent.style.display = "none";
        });
    }

    function showTab(tabId) {
        hideTabs();
        document.getElementById(tabId).style.display = "block";
    }

    function handleTabClick(tab) {
        const tabId = tab.getAttribute("data-text").toLowerCase();
        showTab(tabId);
    }

    tabs.forEach(tab => {
        tab.addEventListener("click", function() {
            handleTabClick(this);
        });
    });

    // Mostrar el contenido de la primera pestaña al cargar la página
    showTab("home");
});
