# Responsive Nav Menu for Elementor

An Elementor add-on that registers a **Responsive Nav Menu** widget. It renders any WordPress menu as a responsive navigation: a horizontal menu with hover dropdowns on desktop, and an accessible fullscreen burger overlay with collapsible submenus on mobile. It can also be switched to a vertical layout (handy for footers). All styling and behaviour are configurable through the Elementor editor.

## Features

- Native Elementor widget under the **Responsive Nav Menu** category.
- Pick any registered WordPress menu from a dropdown.
- **Horizontal layout**: hover dropdowns on desktop, fullscreen burger overlay on mobile.
- **Vertical layout**: an always-visible stacked list with statically expanded submenus and no burger — ideal for footers and sidebars.
- Optional **per-item icon** (Font Awesome or uploaded SVG) with configurable position, size, spacing, and color. Icons follow the link hover/active color automatically.
- Hover dropdowns kept open by an invisible bridge, with a configurable gap from the main menu.
- Fullscreen mobile overlay toggled by an animated burger button.
- Collapsible submenus on mobile with auto-generated toggle buttons.
- Accessibility-friendly: `aria-expanded`, `aria-controls`, `aria-label`, and `Escape` to close.
- Body scroll lock while the mobile menu is open.
- Rich style controls: link colors, typography, item spacing, dropdown styling, item icon, burger colors, and mobile panel background.
- Live preview inside the Elementor editor.
- No build step required — plain CSS and vanilla JavaScript.

## Requirements

- WordPress 5.8+
- PHP 7.4+
- Elementor 3.5+

## Installation

1. Copy the `responsive-nav-menu-elementor` folder into `wp-content/plugins/`.
2. Activate **Responsive Nav Menu for Elementor** from the WordPress admin Plugins screen.
3. Make sure Elementor is installed and active, and that at least one menu exists under **Appearance → Menus**.

## Usage

1. Edit a page or template with Elementor.
2. Search for **Responsive Nav Menu** in the widgets panel and drag it onto the canvas.
3. In **Content → Menu**, choose the menu to display, pick a **Layout** (horizontal or vertical), optionally set a per-item icon, and adjust the accessibility labels.
4. Switch to the **Style** tab to customise colors, typography, spacing, the dropdown, the item icon, and the mobile burger.

### Using it in a footer

Drop the widget into a footer (or any narrow column) and set **Content → Menu → Layout** to **Vertical**. The burger is disabled, the items stack into a column, and submenus are shown as a statically expanded, indented list.

## Controls

### Content

| Control                 | Description                                                      |
| ----------------------- | --------------------------------------------------------------- |
| Select Menu             | The WordPress menu to render.                                   |
| Layout                  | `Horizontal` (with mobile burger) or `Vertical` (e.g. footer).  |
| Item Icon               | Optional icon (Font Awesome or uploaded SVG) for every top-level item. |
| Icon Position           | Show the icon before or after the link text.                    |
| Navigation ARIA label   | Accessible label for the `<nav>` element.                       |
| Open button label       | Accessible label for the burger when the menu is closed.        |
| Close button label      | Accessible label for the burger when the menu is open.          |

### Style

| Control                       | Description                                                     |
| ----------------------------- | --------------------------------------------------------------- |
| Link Color                    | Default link color.                                             |
| Link Color (Hover)            | Color for hovered menu items (icons follow it too).             |
| Link Color (Active)           | Color of the current page item. Match it to the default link color to disable highlighting. |
| Typography                    | Font settings for menu links.                                   |
| Items Gap                     | Spacing between top-level items.                                |
| **Dropdown (Submenu)**        |                                                                 |
| Gap From Main Menu            | Vertical distance between the dropdown and the main menu.       |
| Dropdown Background           | Submenu panel background color.                                 |
| Dropdown Border Color         | Submenu panel border color.                                     |
| Dropdown Min Width            | Minimum width of the dropdown panel.                            |
| Dropdown Link Color           | Submenu link color.                                             |
| Dropdown Link Color (Hover/Active) | Submenu link color on hover / for current items.           |
| Dropdown Item Background (Hover)   | Background of a submenu item on hover.                     |
| Dropdown Item Divider Color   | Color of the dividers between submenu items.                    |
| Dropdown Typography           | Font settings for submenu links.                                |
| Dropdown Item Padding         | Inner padding of submenu items.                                 |
| **Item Icon**                 | (shown when an icon is selected)                                |
| Icon Size                     | Icon size.                                                      |
| Icon Spacing                  | Gap between the icon and the link text.                         |
| Icon Color                    | Default icon color (overridden by the link hover/active color). |
| **Mobile Burger**             |                                                                 |
| Burger Lines Color            | Color of the burger icon lines.                                 |
| Burger Background             | Background of the burger button.                                |
| Mobile Panel Background       | Background of the fullscreen mobile overlay.                    |

## Customisation

The mobile breakpoint is `1024px` and is defined in both the stylesheet (media query) and the script (`BREAKPOINT` constant). The vertical layout adds a `rnm-menu--vertical` modifier class and is always-on regardless of breakpoint. Markup uses BEM-style class names (`rnm-menu`, `rnm-menu__nav`, `rnm-menu__burger`, `rnm-menu__icon`, …) that can be targeted with additional CSS if needed. Per-item icons are injected through a custom `Walker_Nav_Menu` (`rnme_get_nav_walker`).

## File structure

```text
responsive-nav-menu-elementor/
├── assets/
│   ├── css/
│   │   └── responsive-nav-menu.css
│   └── js/
│       └── responsive-nav-menu.js
├── includes/
│   └── class-responsive-nav-menu-widget.php
├── responsive-nav-menu-elementor.php
└── README.md
```

## License

GPL-2.0-or-later.
