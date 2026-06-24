(function () {
	"use strict";

	var BREAKPOINT = 1024;

	function isMobile() {
		return window.innerWidth <= BREAKPOINT;
	}

	function initMenu(root) {
		var burger = root.querySelector(".rnm-menu__burger");
		var nav = root.querySelector(".rnm-menu__nav");

		if (!burger || !nav) {
			return;
		}

		var openLabel = burger.getAttribute("data-open-label") || "Open menu";
		var closeLabel = burger.getAttribute("data-close-label") || "Close menu";
		var parents = nav.querySelectorAll(".menu-item-has-children");

		function setState(isOpen) {
			root.classList.toggle("rnm-menu--open", isOpen);
			burger.setAttribute("aria-expanded", isOpen ? "true" : "false");
			burger.setAttribute("aria-label", isOpen ? closeLabel : openLabel);
			document.documentElement.classList.toggle("rnm-menu-lock", isOpen);
			document.body.classList.toggle("rnm-menu-lock", isOpen);
		}

		function closeMenu() {
			setState(false);
		}

		function toggleMenu() {
			setState(!root.classList.contains("rnm-menu--open"));
		}

		burger.addEventListener("click", function (event) {
			event.preventDefault();
			toggleMenu();
		});

		function clearDesktopHover() {
			Array.prototype.forEach.call(parents, function (item) {
				item.classList.remove("rnm-submenu--hover");
			});
		}

		function setupDesktopHover() {
			Array.prototype.forEach.call(parents, function (item) {
				if (item.dataset.rnmDesktopHover === "1") {
					return;
				}

				item.dataset.rnmDesktopHover = "1";

				item.addEventListener("mouseenter", function () {
					if (isMobile()) {
						return;
					}
					item.classList.add("rnm-submenu--hover");
				});

				item.addEventListener("mouseleave", function () {
					item.classList.remove("rnm-submenu--hover");
				});
			});
		}

		function setupSubToggles() {
			Array.prototype.forEach.call(parents, function (item) {
				var sub = item.querySelector(":scope > .sub-menu");
				var toggle = item.querySelector(":scope > .rnm-menu__sub-toggle");
				var link = item.querySelector(":scope > a");

				if (!sub) {
					return;
				}

				if (!isMobile()) {
					item.classList.remove("rnm-submenu--open");
					if (toggle) {
						toggle.remove();
					}
					return;
				}

				if (toggle) {
					return;
				}

				toggle = document.createElement("button");
				toggle.type = "button";
				toggle.className = "rnm-menu__sub-toggle";
				toggle.setAttribute("aria-expanded", "false");
				toggle.setAttribute("aria-label", "Toggle submenu");
				toggle.innerHTML = "<span></span>";

				if (link) {
					link.insertAdjacentElement("afterend", toggle);
				} else {
					item.insertBefore(toggle, sub);
				}

				function toggleSub() {
					var isOpen = item.classList.toggle("rnm-submenu--open");
					toggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
				}

				toggle.addEventListener("click", function (event) {
					event.preventDefault();
					event.stopPropagation();
					toggleSub();
				});

				if (link && link.dataset.rnmSubmenuLinkBound !== "1") {
					link.dataset.rnmSubmenuLinkBound = "1";
					link.addEventListener("click", function (event) {
						var href = (link.getAttribute("href") || "").trim();
						var noTarget = href === "" || href === "#" || href.indexOf("javascript:") === 0;

						if (isMobile() && noTarget) {
							event.preventDefault();
							toggleSub();
						}
					});
				}
			});
		}

		setupDesktopHover();
		setupSubToggles();

		nav.addEventListener("click", function (event) {
			if (!isMobile()) {
				return;
			}

			var target = event.target;

			if (target.closest(".rnm-menu__sub-toggle")) {
				return;
			}

			var anchor = target.closest("a");

			if (!anchor) {
				return;
			}

			var parentItem = anchor.parentElement;
			var href = (anchor.getAttribute("href") || "").trim();
			var isParentLink =
				parentItem &&
				parentItem.classList &&
				parentItem.classList.contains("menu-item-has-children");

			if (isParentLink && (href === "" || href === "#" || href.indexOf("javascript:") === 0)) {
				return;
			}

			closeMenu();
		});

		document.addEventListener("keydown", function (event) {
			if (event.key === "Escape") {
				closeMenu();
			}
		});

		window.addEventListener("resize", function () {
			setupSubToggles();
			clearDesktopHover();

			if (isMobile()) {
				return;
			}

			closeMenu();

			Array.prototype.forEach.call(parents, function (item) {
				item.classList.remove("rnm-submenu--open");
			});
		});
	}

	function initAll(context) {
		var scope = context || document;
		var menus = scope.querySelectorAll(".rnm-menu[data-rnm-menu]");

		Array.prototype.forEach.call(menus, function (menu) {
			if (menu.dataset.rnmInit === "1") {
				return;
			}
			menu.dataset.rnmInit = "1";
			initMenu(menu);
		});
	}

	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", function () {
			initAll();
		});
	} else {
		initAll();
	}

	if (window.jQuery) {
		window.jQuery(window).on("elementor/frontend/init", function () {
			if (!window.elementorFrontend || !window.elementorFrontend.hooks) {
				return;
			}

			window.elementorFrontend.hooks.addAction(
				"frontend/element_ready/responsive_nav_menu.default",
				function ($scope) {
					initAll($scope && $scope[0] ? $scope[0] : undefined);
				}
			);
		});
	}
})();
