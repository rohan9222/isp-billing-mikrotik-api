const CodePagol = {
    
    initConfig: function () {
        const CONFIG = {
            isNavbarVerticalCollapsed: true,
            theme: "auto",
            isRTL: false,
            isFluid: true,
            navbarStyle: "transparent",
            navbarPosition: "vertical",
        };

        Object.keys(CONFIG).forEach(function (key) {
            if (localStorage.getItem(key) === null) {
                localStorage.setItem(key, CONFIG[key]);
            }
        });
    },


/**
 * Toggles the 'navbar-vertical-collapsed' class on the HTML element,
 * updates the local storage with the new collapsed state, and logs
 * the state to the console. Additionally, dispatches a custom event
 * 'navbarVerticalToggled' with the collapsed state details.
 */

    handleNavbarClick: function () {
        const HTML = document.querySelector("html");
        HTML.classList.toggle("navbar-vertical-collapsed");
        const isCollapsed = HTML.classList.contains("navbar-vertical-collapsed");
        localStorage.setItem("isNavbarVerticalCollapsed", isCollapsed);

        document.dispatchEvent(
            new CustomEvent("navbarVerticalToggled", {
                detail: { isCollapsed: isCollapsed },
            })
        );
    },


    navbarComboInit: function () {
        const HTML = document.querySelector("html");
        const navbarVertical = document.querySelector(".navbar-vertical");
        const navbarVerticalToggle = document.querySelector(".navbar-vertical-toggle");

        if (navbarVerticalToggle && navbarVertical) {
            // Remove previous listener (if any)
            navbarVerticalToggle.removeEventListener(
                "click",
                this.handleNavbarClick
            );

            // Add new click listener
            navbarVerticalToggle.addEventListener(
                "click",
                this.handleNavbarClick
            );
        }

        // Restore collapse state on load
        const isCollapsed = JSON.parse(localStorage.getItem("isNavbarVerticalCollapsed"));
        if (isCollapsed) {
            HTML.classList.add("navbar-vertical-collapsed");
        } else {
            HTML.classList.remove("navbar-vertical-collapsed");
        }
    },

    init: function () {
        this.initConfig();
        this.navbarComboInit();
    },

    otherMethods: function () {
        this.navbarComboInit();
    },
};

export default CodePagol;
