<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class RNME_Menu_Widget extends Widget_Base {

	public function get_name() {
		return 'responsive_nav_menu';
	}

	public function get_title() {
		return esc_html__( 'Responsive Nav Menu', 'responsive-nav-menu-elementor' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return array( 'responsive-nav-menu', 'general' );
	}

	public function get_keywords() {
		return array( 'menu', 'nav', 'navigation', 'burger', 'responsive', 'mobile' );
	}

	public function get_style_depends() {
		return array( 'responsive-nav-menu' );
	}

	public function get_script_depends() {
		return array( 'responsive-nav-menu' );
	}

	private function get_available_menus() {
		$menus   = wp_get_nav_menus();
		$options = array();

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	protected function register_controls() {
		$menus = $this->get_available_menus();

		$this->start_controls_section(
			'section_menu',
			array(
				'label' => esc_html__( 'Menu', 'responsive-nav-menu-elementor' ),
			)
		);

		if ( empty( $menus ) ) {
			$this->add_control(
				'no_menus_notice',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => sprintf(
						esc_html__( 'There are no menus yet. %s', 'responsive-nav-menu-elementor' ),
						'<a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '" target="_blank">' . esc_html__( 'Create one', 'responsive-nav-menu-elementor' ) . '</a>'
					),
				)
			);
		} else {
			$this->add_control(
				'menu',
				array(
					'label'   => esc_html__( 'Select Menu', 'responsive-nav-menu-elementor' ),
					'type'    => Controls_Manager::SELECT,
					'options' => $menus,
					'default' => array_keys( $menus )[0],
				)
			);
		}

		$this->add_control(
			'layout',
			array(
				'label'       => esc_html__( 'Layout', 'responsive-nav-menu-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'horizontal',
				'options'     => array(
					'horizontal' => esc_html__( 'Horizontal (with mobile burger)', 'responsive-nav-menu-elementor' ),
					'vertical'   => esc_html__( 'Vertical (e.g. footer)', 'responsive-nav-menu-elementor' ),
				),
				'description' => esc_html__( 'Vertical stacks all items in a column and disables the mobile burger.', 'responsive-nav-menu-elementor' ),
			)
		);

		$this->add_control(
			'item_icon',
			array(
				'label'       => esc_html__( 'Item Icon', 'responsive-nav-menu-elementor' ),
				'type'        => Controls_Manager::ICONS,
				'description' => esc_html__( 'Optional icon shown next to every top-level menu item.', 'responsive-nav-menu-elementor' ),
			)
		);

		$this->add_control(
			'icon_position',
			array(
				'label'     => esc_html__( 'Icon Position', 'responsive-nav-menu-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'before',
				'options'   => array(
					'before' => esc_html__( 'Before text', 'responsive-nav-menu-elementor' ),
					'after'  => esc_html__( 'After text', 'responsive-nav-menu-elementor' ),
				),
				'condition' => array(
					'item_icon[value]!' => '',
				),
			)
		);

		$this->add_control(
			'aria_label',
			array(
				'label'   => esc_html__( 'Navigation ARIA label', 'responsive-nav-menu-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Main menu', 'responsive-nav-menu-elementor' ),
			)
		);

		$this->add_control(
			'open_label',
			array(
				'label'   => esc_html__( 'Open button label', 'responsive-nav-menu-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Open menu', 'responsive-nav-menu-elementor' ),
			)
		);

		$this->add_control(
			'close_label',
			array(
				'label'   => esc_html__( 'Close button label', 'responsive-nav-menu-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Close menu', 'responsive-nav-menu-elementor' ),
			)
		);

		$this->end_controls_section();

		$this->register_style_controls();
	}

	private function register_style_controls() {
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Menu Style', 'responsive-nav-menu-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => esc_html__( 'Link Color', 'responsive-nav-menu-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rnm-menu__nav a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_hover_color',
			array(
				'label'     => esc_html__( 'Link Color (Hover)', 'responsive-nav-menu-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rnm-menu__nav a:hover'                     => 'color: {{VALUE}};',
					'{{WRAPPER}} .rnm-menu__nav a:hover .rnm-menu__icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .rnm-menu__nav a:hover .rnm-menu__icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_active_color',
			array(
				'label'       => esc_html__( 'Link Color (Active)', 'responsive-nav-menu-elementor' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Color of the current page item. Set it to the default link color if you do not want the active item highlighted.', 'responsive-nav-menu-elementor' ),
				'selectors'   => array(
					'{{WRAPPER}} .rnm-menu__nav .current-menu-item > a'                        => 'color: {{VALUE}};',
					'{{WRAPPER}} .rnm-menu__nav .current-menu-ancestor > a'                    => 'color: {{VALUE}};',
					'{{WRAPPER}} .rnm-menu__nav .current-menu-item > a .rnm-menu__icon'         => 'color: {{VALUE}};',
					'{{WRAPPER}} .rnm-menu__nav .current-menu-item > a .rnm-menu__icon svg'     => 'fill: {{VALUE}};',
					'{{WRAPPER}} .rnm-menu__nav .current-menu-ancestor > a .rnm-menu__icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .rnm-menu__nav .current-menu-ancestor > a .rnm-menu__icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'link_typography',
				'selector' => '{{WRAPPER}} .rnm-menu__nav a',
			)
		);

		$this->add_responsive_control(
			'menu_gap',
			array(
				'label'      => esc_html__( 'Items Gap', 'responsive-nav-menu-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rnm-menu__nav > div > ul' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rnm-menu__nav > ul'       => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'dropdown_heading',
			array(
				'label'     => esc_html__( 'Dropdown (Submenu)', 'responsive-nav-menu-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'dropdown_gap',
			array(
				'label'      => esc_html__( 'Gap From Main Menu', 'responsive-nav-menu-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rnm-menu__nav .menu-item-has-children > .sub-menu' => '--rnm-submenu-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'dropdown_bg',
			array(
				'label'     => esc_html__( 'Dropdown Background', 'responsive-nav-menu-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rnm-menu__nav .menu-item-has-children > .sub-menu' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dropdown_border_color',
			array(
				'label'     => esc_html__( 'Dropdown Border Color', 'responsive-nav-menu-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rnm-menu__nav .menu-item-has-children > .sub-menu' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dropdown_width',
			array(
				'label'      => esc_html__( 'Dropdown Min Width', 'responsive-nav-menu-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 160,
						'max' => 600,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rnm-menu__nav .menu-item-has-children > .sub-menu' => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'dropdown_link_color',
			array(
				'label'     => esc_html__( 'Dropdown Link Color', 'responsive-nav-menu-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rnm-menu__nav .menu-item-has-children > .sub-menu li a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dropdown_link_hover_color',
			array(
				'label'     => esc_html__( 'Dropdown Link Color (Hover / Active)', 'responsive-nav-menu-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rnm-menu__nav .menu-item-has-children > .sub-menu li a:hover'                => 'color: {{VALUE}};',
					'{{WRAPPER}} .rnm-menu__nav .menu-item-has-children > .sub-menu .current-menu-item > a'    => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dropdown_link_hover_bg',
			array(
				'label'     => esc_html__( 'Dropdown Item Background (Hover)', 'responsive-nav-menu-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rnm-menu__nav .menu-item-has-children > .sub-menu li a:hover' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dropdown_divider_color',
			array(
				'label'     => esc_html__( 'Dropdown Item Divider Color', 'responsive-nav-menu-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rnm-menu__nav .menu-item-has-children > .sub-menu li a' => 'border-bottom-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'dropdown_typography',
				'selector' => '{{WRAPPER}} .rnm-menu__nav .menu-item-has-children > .sub-menu li a',
			)
		);

		$this->add_responsive_control(
			'dropdown_item_padding',
			array(
				'label'      => esc_html__( 'Dropdown Item Padding', 'responsive-nav-menu-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rnm-menu__nav .menu-item-has-children > .sub-menu li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_heading',
			array(
				'label'     => esc_html__( 'Item Icon', 'responsive-nav-menu-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'item_icon[value]!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'responsive-nav-menu-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 60,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rnm-menu__icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'item_icon[value]!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'icon_gap',
			array(
				'label'      => esc_html__( 'Icon Spacing', 'responsive-nav-menu-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rnm-menu' => '--rnm-icon-gap: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'item_icon[value]!' => '',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'responsive-nav-menu-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rnm-menu__icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .rnm-menu__icon svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'item_icon[value]!' => '',
				),
			)
		);

		$this->add_control(
			'burger_heading',
			array(
				'label'     => esc_html__( 'Mobile Burger', 'responsive-nav-menu-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'burger_color',
			array(
				'label'     => esc_html__( 'Burger Lines Color', 'responsive-nav-menu-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rnm-menu__burger-line' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'burger_bg',
			array(
				'label'     => esc_html__( 'Burger Background', 'responsive-nav-menu-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rnm-menu__burger' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'panel_bg',
			array(
				'label'     => esc_html__( 'Mobile Panel Background', 'responsive-nav-menu-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'(tablet){{WRAPPER}} .rnm-menu__nav' => 'background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['menu'] ) ) {
			if ( current_user_can( 'edit_theme_options' ) ) {
				echo '<p>' . esc_html__( 'Please select a menu for this widget.', 'responsive-nav-menu-elementor' ) . '</p>';
			}
			return;
		}

		$icon_html = '';
		$icon      = isset( $settings['item_icon'] ) ? $settings['item_icon'] : array();

		if ( ! empty( $icon['value'] ) && class_exists( '\Elementor\Icons_Manager' ) ) {
			ob_start();
			\Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
			$icon_inner = ob_get_clean();

			if ( $icon_inner ) {
				$icon_html = '<span class="rnm-menu__icon">' . $icon_inner . '</span>';
			}
		}

		echo rnme_render_menu( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			array(
				'menu'          => $settings['menu'],
				'aria_label'    => isset( $settings['aria_label'] ) ? $settings['aria_label'] : '',
				'open_label'    => isset( $settings['open_label'] ) ? $settings['open_label'] : '',
				'close_label'   => isset( $settings['close_label'] ) ? $settings['close_label'] : '',
				'layout'        => isset( $settings['layout'] ) ? $settings['layout'] : 'horizontal',
				'icon_html'     => $icon_html,
				'icon_position' => isset( $settings['icon_position'] ) ? $settings['icon_position'] : 'before',
			)
		);
	}
}
