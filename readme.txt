=== Responsive Nav Menu for Elementor ===
Contributors: valentynabaranova
Tags: elementor, menu, navigation, responsive menu, mobile menu
Requires at least: 5.8
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.0.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A lightweight Elementor widget that turns any WordPress menu into an accessible responsive navigation with a fullscreen mobile burger.

== Description ==

**Responsive Nav Menu for Elementor** adds a single, focused Elementor widget that renders any WordPress menu as a responsive navigation. On desktop you get a horizontal menu with hover dropdowns; on mobile you get an accessible fullscreen burger overlay with collapsible submenus. You can also switch to a vertical layout, which is handy for footers and sidebars.

It does one thing and does it well: no bloated all-in-one add-on pack, no build step, just clean CSS and vanilla JavaScript. Everything is configurable directly in the Elementor editor.

= Key features =

* Native Elementor widget under its own **Responsive Nav Menu** category.
* Pick any registered WordPress menu from a dropdown.
* **Horizontal layout**: hover dropdowns on desktop, fullscreen burger overlay on mobile.
* **Vertical layout**: an always-visible stacked list with expanded submenus and no burger, ideal for footers.
* Optional **per-item icon** (Font Awesome or uploaded SVG) with configurable position, size, spacing, and color.
* Hover dropdowns kept open by an invisible bridge, with a configurable gap from the main menu.
* Animated burger button toggling a fullscreen mobile overlay.
* Collapsible submenus on mobile with auto-generated toggle buttons.
* Accessibility-friendly: `aria-expanded`, `aria-controls`, `aria-label`, and `Escape` to close.
* Body scroll lock while the mobile menu is open.
* Rich style controls: link colors, typography, item spacing, dropdown styling, item icon, burger colors, and the mobile panel background.
* Live preview inside the Elementor editor.
* No build step required, plain CSS and vanilla JavaScript.

= Requirements =

* WordPress 5.8 or higher
* PHP 7.4 or higher
* Elementor 3.5 or higher

== Installation ==

1. Make sure [Elementor](https://wordpress.org/plugins/elementor/) is installed and active.
2. In your WordPress admin, go to **Plugins → Add New** and search for "Responsive Nav Menu for Elementor", or upload the plugin ZIP via **Plugins → Add New → Upload Plugin**.
3. Activate **Responsive Nav Menu for Elementor**.
4. Create at least one menu under **Appearance → Menus**.
5. Edit a page or template with Elementor, search for **Responsive Nav Menu** in the widgets panel, and drag it onto the canvas.

== Frequently Asked Questions ==

= Does this plugin require Elementor? =

Yes. The plugin registers an Elementor widget, so Elementor must be installed and active. If Elementor is missing, the plugin shows an admin notice and does nothing else.

= Does it work with Elementor free or only Elementor Pro? =

It works with the free version of Elementor. Elementor Pro is not required.

= Which menu does it display? =

Any menu you create under **Appearance → Menus**. You select the menu from a dropdown inside the widget.

= Can I use it in a footer? =

Yes. Drop the widget into a footer or any narrow column and set the **Layout** to **Vertical**. The burger is disabled, items stack into a column, and submenus are shown as an expanded, indented list.

= Can I add icons to menu items? =

Yes. You can set an optional Font Awesome icon or uploaded SVG that appears next to every top-level menu item, and control its position, size, spacing, and color.

= Is the mobile menu accessible? =

Yes. The burger button uses `aria-expanded`, `aria-controls`, and an `aria-label`, the navigation has an `aria-label`, the overlay can be closed with the `Escape` key, and body scrolling is locked while the menu is open.

= Can I change the mobile breakpoint? =

The mobile breakpoint is `1024px`, defined in both the stylesheet (media query) and the script (`BREAKPOINT` constant). You can adjust it with custom code or override styles using the BEM class names (`rnm-menu`, `rnm-menu__nav`, `rnm-menu__burger`, etc.).

== Screenshots ==

1. Horizontal desktop menu with hover dropdowns.
2. Fullscreen mobile burger overlay with collapsible submenus.
3. Vertical layout used in a footer.
4. Style controls in the Elementor editor.

== Changelog ==

= 1.0.4 =
* Improved active/current menu item styling for dropdown links.
* Minor style control refinements.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.4 =
Style refinements for dropdown and active menu items.
