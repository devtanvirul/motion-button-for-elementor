<?php
/** Elementor widget controls and safe frontend markup. */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Raddito_Motion_Button_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'raddito-motion-button'; }
    public function get_title() { return esc_html__( 'Motion Button', 'raddito-motion-button' ); }
    public function get_icon() { return 'eicon-button'; }
    public function get_categories() { return array( 'basic' ); }
    public function get_keywords() { return array( 'button', 'motion', 'animated', 'directional', 'hover', 'tanvirul' ); }
    public function get_style_depends() { return array( 'rmb-button' ); }
    public function get_script_depends() { return array( 'rmb-button' ); }

    protected function register_controls() {
        $c = \Elementor\Controls_Manager::class;
        $accent = \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_ACCENT;
        $text_global = \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_TEXT;

        /* ===== CONTENT ===== */
        $this->start_controls_section( 'content', array(
            'label' => esc_html__( 'Content & Link', 'raddito-motion-button' ),
            'tab' => $c::TAB_CONTENT,
        ) );
        $this->add_control( 'label', array(
            'label' => esc_html__( 'Button Text', 'raddito-motion-button' ),
            'type' => $c::TEXT,
            'default' => 'Start a Project',
            'placeholder' => 'Button label',
            'dynamic' => array( 'active' => true ),
            'label_block' => true,
        ) );
        $this->add_control( 'link', array(
            'label' => esc_html__( 'Link', 'raddito-motion-button' ),
            'type' => $c::URL,
            'default' => array( 'url' => '#' ),
            'placeholder' => 'https://example.com',
            'dynamic' => array( 'active' => true ),
        ) );
        $this->add_control( 'icon', array(
            'label' => esc_html__( 'Icon', 'raddito-motion-button' ),
            'type' => $c::ICONS,
            'default' => array( 'value' => 'fas fa-arrow-up', 'library' => 'fa-solid' ),
        ) );
        $this->add_control( 'icon_position', array(
            'label' => esc_html__( 'Icon Position', 'raddito-motion-button' ),
            'type' => $c::CHOOSE,
            'default' => 'after',
            'options' => array(
                'before' => array( 'title' => 'Before', 'icon' => 'eicon-h-align-left' ),
                'after' => array( 'title' => 'After', 'icon' => 'eicon-h-align-right' ),
            ),
            'toggle' => false,
        ) );
        $this->add_control( 'aria_label', array(
            'label' => esc_html__( 'Accessible Label (optional)', 'raddito-motion-button' ),
            'description' => esc_html__( 'Leave blank to use the visible button text.', 'raddito-motion-button' ),
            'type' => $c::TEXT,
            'default' => '',
        ) );
        $this->end_controls_section();

        /* ===== LAYOUT ===== */
        $this->start_controls_section( 'layout', array( 'label' => 'Layout', 'tab' => $c::TAB_STYLE ) );
        $this->add_control( 'spacing_info', array(
            'type' => $c::RAW_HTML,
            'raw' => 'Zero spacing: set Button Padding to 0, Minimum Height to 0, and Text/Icon Gap to 0. The icon itself has independent Width, Height and Padding under Icon Style. Exact Height is optional; leave it empty for automatic sizing.',
            'content_classes' => 'elementor-descriptor',
        ) );
        $this->add_responsive_control( 'alignment', array(
            'label' => 'Alignment',
            'type' => $c::CHOOSE,
            'options' => array(
                'left' => array( 'title' => 'Left', 'icon' => 'eicon-text-align-left' ),
                'center' => array( 'title' => 'Center', 'icon' => 'eicon-text-align-center' ),
                'right' => array( 'title' => 'Right', 'icon' => 'eicon-text-align-right' ),
            ),
            'default' => 'left',
            'selectors_dictionary' => array( 'left' => 'flex-start', 'center' => 'center', 'right' => 'flex-end' ),
            'selectors' => array( '{{WRAPPER}} .rmb-wrap' => 'justify-content: {{VALUE}};' ),
        ) );
        $this->add_responsive_control( 'button_width', array(
            'label' => 'Button Width (optional)', 'type' => $c::SLIDER,
            'size_units' => array( 'px', '%' ),
            'range' => array( 'px' => array( 'min' => 0, 'max' => 1000 ), '%' => array( 'min' => 0, 'max' => 100 ) ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => 'width: {{SIZE}}{{UNIT}};' ),
        ) );
        $this->add_responsive_control( 'min_height', array(
            'label' => 'Minimum Height (0 = None)', 'type' => $c::SLIDER,
            'description' => 'Set to 0 to remove the minimum height. The icon and text may still determine the natural button height.',
            'size_units' => array( 'px', 'rem' ),
            'range' => array( 'px' => array( 'min' => 0, 'max' => 300 ), 'rem' => array( 'min' => 0, 'max' => 20, 'step' => 0.1 ) ),
            'default' => array( 'size' => 64, 'unit' => 'px' ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => 'min-height: {{SIZE}}{{UNIT}};' ),
        ) );
        $this->add_responsive_control( 'button_height', array(
            'label' => 'Exact Button Height (optional)', 'type' => $c::SLIDER,
            'description' => 'Leave empty for natural height. If a custom height seems ineffective, set Minimum Height to 0.',
            'size_units' => array( 'px', 'rem' ),
            'range' => array( 'px' => array( 'min' => 0, 'max' => 400 ), 'rem' => array( 'min' => 0, 'max' => 25, 'step' => 0.1 ) ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => 'height: {{SIZE}}{{UNIT}};' ),
        ) );
        $this->add_responsive_control( 'padding', array(
            'label' => 'Button Padding', 'type' => $c::DIMENSIONS,
            'size_units' => array( 'px', 'em', 'rem' ),
            'default' => array( 'top' => 8, 'right' => 10, 'bottom' => 8, 'left' => 32, 'unit' => 'px', 'isLinked' => false ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
        ) );
        $this->add_responsive_control( 'border_radius', array(
            'label' => 'Border Radius', 'type' => $c::DIMENSIONS,
            'size_units' => array( 'px', '%', 'rem' ),
            'default' => array( 'top' => 100, 'right' => 100, 'bottom' => 100, 'left' => 100, 'unit' => 'px', 'isLinked' => true ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
        ) );
        $this->add_responsive_control( 'content_padding', array(
            'label' => 'Inner Content Padding', 'type' => $c::DIMENSIONS,
            'description' => 'Separate padding around the combined text and icon. 0 removes it completely.',
            'size_units' => array( 'px', 'em', 'rem' ),
            'default' => array( 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'unit' => 'px', 'isLinked' => true ),
            'selectors' => array( '{{WRAPPER}} .rmb-button__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
        ) );
        $this->add_responsive_control( 'content_gap', array(
            'label' => 'Text / Icon Gap', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 80 ) ),
            'default' => array( 'size' => 14, 'unit' => 'px' ),
            'selectors' => array( '{{WRAPPER}} .rmb-button__content' => 'gap: {{SIZE}}px;' ),
        ) );
        $this->add_responsive_control( 'content_justify', array(
            'label' => 'Inner Content Alignment',
            'type' => $c::CHOOSE,
            'options' => array(
                'start' => array( 'title' => 'Start', 'icon' => 'eicon-text-align-left' ),
                'center' => array( 'title' => 'Center', 'icon' => 'eicon-text-align-center' ),
                'end' => array( 'title' => 'End', 'icon' => 'eicon-text-align-right' ),
            ),
            'default' => 'center',
            'selectors_dictionary' => array( 'start' => 'flex-start', 'center' => 'center', 'end' => 'flex-end' ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => 'justify-content: {{VALUE}};' ),
        ) );
        $this->end_controls_section();

        /* ===== TYPOGRAPHY ===== */
        $this->start_controls_section( 'typography', array( 'label' => 'Typography', 'tab' => $c::TAB_STYLE ) );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
            'name' => 'button_typography',
            'selector' => '{{WRAPPER}} .rmb-button__text',
            'global' => array( 'default' => $text_global ),
        ) );
        $this->end_controls_section();

        /* ===== COLORS (normal/hover) ===== */
        $this->start_controls_section( 'colors', array( 'label' => 'Button Colors & Border', 'tab' => $c::TAB_STYLE ) );
        $this->start_controls_tabs( 'color_tabs' );
        $this->start_controls_tab( 'normal_colors', array( 'label' => 'Normal' ) );
        $this->add_control( 'background_color', array(
            'label' => 'Background Color', 'type' => $c::COLOR,
            'global' => array( 'default' => $accent ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => '--rmb-bg: {{VALUE}};' ),
        ) );
        $this->add_control( 'text_color', array(
            'label' => 'Text Color', 'type' => $c::COLOR, 'default' => '#FFFFFF',
            'selectors' => array( '{{WRAPPER}} .rmb-button' => '--rmb-text: {{VALUE}};' ),
        ) );
        $this->add_group_control( \Elementor\Group_Control_Border::get_type(), array(
            'name' => 'button_border', 'selector' => '{{WRAPPER}} .rmb-button',
        ) );
        $this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), array(
            'name' => 'button_shadow', 'selector' => '{{WRAPPER}} .rmb-button',
        ) );
        $this->end_controls_tab();
        $this->start_controls_tab( 'hover_colors', array( 'label' => 'Hover' ) );
        $this->add_control( 'hover_background_color', array(
            'label' => 'Reveal / Hover Background', 'type' => $c::COLOR, 'default' => '#FFFFFF',
            'selectors' => array( '{{WRAPPER}} .rmb-button' => '--rmb-hover-bg: {{VALUE}};' ),
        ) );
        $this->add_control( 'hover_text_color', array(
            'label' => 'Text Color', 'type' => $c::COLOR,
            'global' => array( 'default' => $accent ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => '--rmb-hover-text: {{VALUE}};' ),
        ) );
        $this->add_control( 'hover_border_color', array(
            'label' => 'Border Color', 'type' => $c::COLOR,
            'selectors' => array( '{{WRAPPER}} .rmb-button:hover, {{WRAPPER}} .rmb-button:focus-visible' => 'border-color: {{VALUE}};' ),
        ) );
        $this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), array(
            'name' => 'hover_shadow', 'selector' => '{{WRAPPER}} .rmb-button:hover, {{WRAPPER}} .rmb-button:focus-visible',
        ) );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /* ===== OPTIONAL GRADIENT / IMAGE BACKGROUNDS ===== */
        $this->start_controls_section( 'advanced_backgrounds', array(
            'label' => 'Advanced Backgrounds (Optional)',
            'tab' => $c::TAB_STYLE,
        ) );
        $this->add_control( 'background_help', array(
            'type' => $c::RAW_HTML,
            'raw' => 'Use these controls for gradients or images. If unused, the standard color controls above apply. Hover background styles appear in the animated reveal layer.',
            'content_classes' => 'elementor-descriptor',
        ) );
        $this->add_group_control( \Elementor\Group_Control_Background::get_type(), array(
            'name' => 'advanced_normal_bg',
            'label' => 'Normal Background',
            'types' => array( 'classic', 'gradient' ),
            'selector' => '{{WRAPPER}} .rmb-button',
        ) );
        $this->add_group_control( \Elementor\Group_Control_Background::get_type(), array(
            'name' => 'advanced_hover_bg',
            'label' => 'Hover Reveal Background',
            'types' => array( 'classic', 'gradient' ),
            'selector' => '{{WRAPPER}} .rmb-button__reveal',
        ) );
        $this->end_controls_section();

        /* ===== ICON ===== */
        $this->start_controls_section( 'icon_style', array( 'label' => 'Icon Style', 'tab' => $c::TAB_STYLE ) );
        $this->add_control( 'icon_shape', array(
            'label' => 'Icon Background Shape', 'type' => $c::SELECT,
            'default' => 'circle',
            'options' => array( 'circle' => 'Circle', 'rounded' => 'Rounded Square', 'square' => 'Square', 'none' => 'No Background' ),
        ) );
        $this->add_responsive_control( 'icon_size', array(
            'label' => 'Icon Glyph Size', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 120 ) ),
            'default' => array( 'size' => 20, 'unit' => 'px' ),
            'selectors' => array( '{{WRAPPER}} .rmb-button__glyph' => 'font-size: {{SIZE}}px;', '{{WRAPPER}} .rmb-button__glyph svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ),
        ) );
        $this->add_responsive_control( 'icon_width', array(
            'label' => 'Icon Circle Width', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 180 ) ),
            'default' => array( 'size' => 42, 'unit' => 'px' ),
            'selectors' => array( '{{WRAPPER}} .rmb-button__icon' => 'width: {{SIZE}}px;' ),
        ) );
        $this->add_responsive_control( 'icon_height', array(
            'label' => 'Icon Circle Height', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 180 ) ),
            'default' => array( 'size' => 42, 'unit' => 'px' ),
            'selectors' => array( '{{WRAPPER}} .rmb-button__icon' => 'height: {{SIZE}}px;' ),
        ) );
        $this->add_responsive_control( 'icon_padding', array(
            'label' => 'Icon Circle Padding', 'type' => $c::DIMENSIONS,
            'size_units' => array( 'px', 'em', 'rem' ),
            'default' => array( 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'unit' => 'px', 'isLinked' => true ),
            'selectors' => array( '{{WRAPPER}} .rmb-button__icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
        ) );
        $this->add_responsive_control( 'icon_radius', array(
            'label' => 'Custom Icon Corner Radius', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 70 ) ),
            'default' => array( 'size' => 12, 'unit' => 'px' ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => '--rmb-icon-radius: {{SIZE}}px;' ),
            'condition' => array( 'icon_shape' => 'rounded' ),
        ) );
        $this->add_control( 'icon_base_angle', array(
            'label' => 'Icon Initial Angle (degrees)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => -360, 'max' => 360, 'step' => 1 ) ),
            'default' => array( 'size' => 45, 'unit' => 'px' ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => '--rmb-icon-base-angle: {{SIZE}}deg;' ),
            'description' => '45° turns the default up arrow into an up-right arrow.',
        ) );
        $this->start_controls_tabs( 'icon_colors' );
        $this->start_controls_tab( 'icon_normal', array( 'label' => 'Normal' ) );
        $this->add_control( 'icon_bg', array(
            'label' => 'Icon Background', 'type' => $c::COLOR, 'default' => '#FFFFFF',
            'selectors' => array( '{{WRAPPER}} .rmb-button' => '--rmb-icon-bg: {{VALUE}};' ),
        ) );
        $this->add_control( 'icon_color', array(
            'label' => 'Icon Color', 'type' => $c::COLOR,
            'global' => array( 'default' => $accent ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => '--rmb-icon-color: {{VALUE}};' ),
        ) );
        $this->end_controls_tab();
        $this->start_controls_tab( 'icon_hover', array( 'label' => 'Hover' ) );
        $this->add_control( 'hover_icon_bg', array(
            'label' => 'Icon Background', 'type' => $c::COLOR,
            'global' => array( 'default' => $accent ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => '--rmb-icon-hover-bg: {{VALUE}};' ),
        ) );
        $this->add_control( 'hover_icon_color', array(
            'label' => 'Icon Color', 'type' => $c::COLOR, 'default' => '#FFFFFF',
            'selectors' => array( '{{WRAPPER}} .rmb-button' => '--rmb-icon-hover-color: {{VALUE}};' ),
        ) );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /* ===== ANIMATION ===== */
        $this->start_controls_section( 'animation', array( 'label' => 'Hover Animation', 'tab' => $c::TAB_CONTENT ) );
        $this->add_control( 'reveal_effect', array(
            'label' => 'Background Animation', 'type' => $c::SELECT,
            'default' => 'directional',
            'options' => array(
                'directional' => 'Directional cursor reveal (enter + exit)',
                'left' => 'Wipe from left',
                'right' => 'Wipe from right',
                'top' => 'Wipe from top',
                'bottom' => 'Wipe from bottom',
                'center' => 'Expand from center',
                'diagonal' => 'Diagonal reveal',
                'fade' => 'Soft fade',
                'none' => 'Instant color change',
            ),
        ) );
        $this->add_control( 'enter_duration', array(
            'label' => 'Enter Duration (ms)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 2000, 'step' => 20 ) ),
            'default' => array( 'size' => 700, 'unit' => 'px' ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => '--rmb-enter-ms: {{SIZE}}ms;' ),
        ) );
        $this->add_control( 'leave_duration', array(
            'label' => 'Exit Duration (ms)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 2000, 'step' => 20 ) ),
            'default' => array( 'size' => 620, 'unit' => 'px' ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => '--rmb-leave-ms: {{SIZE}}ms;' ),
        ) );
        $this->add_control( 'ease', array(
            'label' => 'Motion Easing', 'type' => $c::SELECT,
            'default' => 'premium',
            'options' => array( 'premium' => 'Premium smooth', 'standard' => 'Ease in/out', 'gentle' => 'Gentle', 'snappy' => 'Snappy', 'linear' => 'Linear' ),
        ) );
        $this->add_control( 'icon_effect', array(
            'label' => 'Icon Hover Animation', 'type' => $c::SELECT,
            'default' => 'rotate',
            'options' => array( 'rotate' => 'Rotate', 'slide' => 'Slide diagonal', 'scale' => 'Scale up', 'pop' => 'Pop', 'none' => 'None' ),
        ) );
        $this->add_control( 'color_transition_time', array(
            'label' => 'Text / Circle Color Duration (ms)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 1200, 'step' => 20 ) ),
            'default' => array( 'size' => 350, 'unit' => 'px' ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => '--rmb-color-ms: {{SIZE}}ms;' ),
        ) );
        $this->add_control( 'icon_transition_time', array(
            'label' => 'Icon Motion Duration (ms)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 1800, 'step' => 20 ) ),
            'default' => array( 'size' => 500, 'unit' => 'px' ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => '--rmb-icon-ms: {{SIZE}}ms;' ),
        ) );
        $this->add_control( 'icon_hover_rotation', array(
            'label' => 'Icon Rotation on Hover', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => -360, 'max' => 360, 'step' => 1 ) ),
            'default' => array( 'size' => 90, 'unit' => 'px' ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => '--rmb-icon-hover-angle: {{SIZE}}deg;' ),
            'condition' => array( 'icon_effect' => 'rotate' ),
        ) );
        $this->add_control( 'icon_motion_distance', array(
            'label' => 'Icon Slide Distance', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
            'default' => array( 'size' => 4, 'unit' => 'px' ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => '--rmb-icon-slide: {{SIZE}}px;' ),
            'condition' => array( 'icon_effect' => 'slide' ),
        ) );
        $this->add_control( 'hover_lift', array(
            'label' => 'Button Hover Lift (px)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 24 ) ),
            'default' => array( 'size' => 0, 'unit' => 'px' ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => '--rmb-lift: {{SIZE}}px;' ),
        ) );
        $this->add_control( 'hover_scale', array(
            'label' => 'Button Hover Scale (%)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 90, 'max' => 120 ) ),
            'default' => array( 'size' => 100, 'unit' => 'px' ),
            'selectors' => array( '{{WRAPPER}} .rmb-button' => '--rmb-scale: calc({{SIZE}} / 100);' ),
        ) );
        $this->add_control( 'magnetic', array(
            'label' => 'Magnetic Content Follow', 'type' => $c::SWITCHER,
            'label_on' => 'Yes', 'label_off' => 'No', 'return_value' => 'yes', 'default' => '',
        ) );
        $this->add_control( 'magnetic_strength', array(
            'label' => 'Magnetic Strength', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 1, 'max' => 20 ) ),
            'default' => array( 'size' => 5, 'unit' => 'px' ),
            'condition' => array( 'magnetic' => 'yes' ),
        ) );
        $this->add_control( 'click_ripple', array(
            'label' => 'Click Ripple', 'type' => $c::SWITCHER,
            'return_value' => 'yes', 'default' => '',
        ) );
        $this->end_controls_section();

        /* ===== TEXT HOVER MOTION (GSAP) ===== */
        $this->start_controls_section( 'text_motion', array(
            'label' => 'Text Hover Motion (GSAP)',
            'tab' => $c::TAB_CONTENT,
        ) );
        $this->add_control( 'text_effect', array(
            'label' => 'Text Animation', 'type' => $c::SELECT,
            'default' => 'lift',
            'options' => array(
                'none' => 'None (Off)',
                'lift' => 'Subtle Lift',
                'rise' => 'Fade & Rise',
                'scale' => 'Gentle Scale',
                'wave' => 'Letter Wave',
                'blur' => 'Soft Blur Reveal',
            ),
        ) );
        $this->add_control( 'text_duration', array(
            'label' => 'Enter Duration (ms)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 1600, 'step' => 10 ) ),
            'default' => array( 'size' => 420, 'unit' => 'px' ),
            'condition' => array( 'text_effect!' => 'none' ),
        ) );
        $this->add_control( 'text_exit_duration', array(
            'label' => 'Exit Duration (ms)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 1600, 'step' => 10 ) ),
            'default' => array( 'size' => 340, 'unit' => 'px' ),
            'condition' => array( 'text_effect!' => 'none' ),
        ) );
        $this->add_control( 'text_distance', array(
            'label' => 'Movement (px)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 32, 'step' => 1 ) ),
            'default' => array( 'size' => 3, 'unit' => 'px' ),
            'condition' => array( 'text_effect' => array( 'lift', 'rise', 'wave', 'blur' ) ),
        ) );
        $this->add_control( 'text_scale', array(
            'label' => 'Hover Scale (%)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 100, 'max' => 120, 'step' => 1 ) ),
            'default' => array( 'size' => 103, 'unit' => 'px' ),
            'condition' => array( 'text_effect' => 'scale' ),
        ) );
        $this->add_control( 'text_stagger', array(
            'label' => 'Letter Stagger (ms)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 150, 'step' => 5 ) ),
            'default' => array( 'size' => 25, 'unit' => 'px' ),
            'condition' => array( 'text_effect' => 'wave' ),
        ) );
        $this->add_control( 'text_start_opacity', array(
            'label' => 'Reveal Start Opacity (%)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 100, 'step' => 5 ) ),
            'default' => array( 'size' => 75, 'unit' => 'px' ),
            'condition' => array( 'text_effect' => array( 'rise', 'blur' ) ),
        ) );
        $this->add_control( 'text_ease', array(
            'label' => 'Text Easing', 'type' => $c::SELECT,
            'default' => 'soft',
            'options' => array(
                'soft' => 'Soft (power2.out)',
                'smooth' => 'Smooth (power3.out)',
                'sine' => 'Gentle (sine.out)',
                'back' => 'Light Spring (back.out)',
                'linear' => 'Linear',
            ),
            'condition' => array( 'text_effect!' => 'none' ),
        ) );
        $this->add_control( 'text_help', array(
            'type' => $c::RAW_HTML,
            'raw' => 'The default is a very small 3px lift. GSAP runs on hover and keyboard focus. Motion is disabled for visitors who prefer reduced motion. Text and other layout spacing stay independent.',
            'content_classes' => 'elementor-descriptor',
        ) );
        $this->end_controls_section();

        /* ===== MOBILE MOTION ===== */
        $this->start_controls_section( 'mobile_motion', array(
            'label' => 'Mobile Motion (Touch & Tap)',
            'tab' => $c::TAB_CONTENT,
        ) );
        $this->add_control( 'mobile_help', array(
            'type' => $c::RAW_HTML,
            'raw' => 'Mobile motion settings are independent from desktop. Touch interactions never require a second tap and never delay navigation. On linked buttons, page navigation can naturally interrupt the visual animation. Select <strong>Off</strong> to disable touch motion.',
            'content_classes' => 'elementor-descriptor',
        ) );
        $this->add_control( 'mobile_behavior', array(
            'label' => 'Touch Animation Behavior', 'type' => $c::SELECT,
            'default' => 'tap',
            'options' => array(
                'tap' => 'Tap: animate, then auto-reset',
                'press' => 'Press: animate while held',
                'off' => 'Off: no touch motion',
            ),
        ) );
        $this->add_control( 'mobile_scope', array(
            'label' => 'Apply Mobile Settings To', 'type' => $c::SELECT,
            'default' => 'phone',
            'options' => array(
                'phone' => 'Phone width only',
                'touch' => 'Phone width + all touch screens',
            ),
        ) );
        $this->add_control( 'mobile_breakpoint', array(
            'label' => 'Phone Max Width (px)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 320, 'max' => 1200, 'step' => 1 ) ),
            'default' => array( 'size' => 767, 'unit' => 'px' ),
            'description' => 'Default 767px. Match your Elementor mobile breakpoint if customized.',
        ) );
        $this->add_control( 'mobile_hold', array(
            'label' => 'Tap Auto-Reset Delay (ms)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 2500, 'step' => 25 ) ),
            'default' => array( 'size' => 500, 'unit' => 'px' ),
            'condition' => array( 'mobile_behavior' => 'tap' ),
        ) );
        $this->add_control( 'mobile_background_heading', array(
            'label' => 'Background Animation', 'type' => $c::HEADING, 'separator' => 'before',
        ) );
        $this->add_control( 'mobile_reveal_effect', array(
            'label' => 'Mobile Background Effect', 'type' => $c::SELECT,
            'default' => 'center',
            'options' => array(
                'inherit' => 'Inherit Desktop',
                'directional' => 'Directional from touch point',
                'left' => 'Wipe from Left', 'right' => 'Wipe from Right',
                'top' => 'Wipe from Top', 'bottom' => 'Wipe from Bottom',
                'center' => 'Expand from Center', 'diagonal' => 'Diagonal Reveal',
                'fade' => 'Soft Fade', 'none' => 'Instant Color Change',
            ),
        ) );
        $this->add_control( 'mobile_enter_duration', array(
            'label' => 'Mobile Enter Duration (ms)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 2000, 'step' => 20 ) ),
            'default' => array( 'size' => 420, 'unit' => 'px' ),
        ) );
        $this->add_control( 'mobile_leave_duration', array(
            'label' => 'Mobile Exit Duration (ms)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 2000, 'step' => 20 ) ),
            'default' => array( 'size' => 360, 'unit' => 'px' ),
        ) );
        $this->add_control( 'mobile_ease', array(
            'label' => 'Mobile Reveal Easing', 'type' => $c::SELECT,
            'default' => 'premium',
            'options' => array( 'premium' => 'Premium smooth', 'standard' => 'Ease in/out', 'gentle' => 'Gentle', 'snappy' => 'Snappy', 'linear' => 'Linear' ),
        ) );
        $this->add_control( 'mobile_icon_heading', array(
            'label' => 'Icon Animation', 'type' => $c::HEADING, 'separator' => 'before',
        ) );
        $this->add_control( 'mobile_icon_effect', array(
            'label' => 'Mobile Icon Effect', 'type' => $c::SELECT,
            'default' => 'rotate',
            'options' => array( 'inherit' => 'Inherit Desktop', 'rotate' => 'Rotate', 'slide' => 'Slide Diagonal', 'scale' => 'Scale Up', 'pop' => 'Pop', 'none' => 'None' ),
        ) );
        $this->add_control( 'mobile_icon_rotation', array(
            'label' => 'Mobile Icon Rotation (°)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => -360, 'max' => 360, 'step' => 1 ) ),
            'default' => array( 'size' => 90, 'unit' => 'px' ),
            'condition' => array( 'mobile_icon_effect' => 'rotate' ),
        ) );
        $this->add_control( 'mobile_icon_duration', array(
            'label' => 'Mobile Icon Duration (ms)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 1800, 'step' => 20 ) ),
            'default' => array( 'size' => 400, 'unit' => 'px' ),
        ) );
        $this->add_control( 'mobile_icon_slide', array(
            'label' => 'Mobile Icon Slide (px)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
            'default' => array( 'size' => 4, 'unit' => 'px' ),
            'condition' => array( 'mobile_icon_effect' => 'slide' ),
        ) );
        $this->add_control( 'mobile_text_heading', array(
            'label' => 'Text Animation (GSAP)', 'type' => $c::HEADING, 'separator' => 'before',
        ) );
        $this->add_control( 'mobile_text_effect', array(
            'label' => 'Mobile Text Effect', 'type' => $c::SELECT,
            'default' => 'lift',
            'options' => array( 'inherit' => 'Inherit Desktop', 'none' => 'Off', 'lift' => 'Subtle Lift', 'rise' => 'Fade & Rise', 'scale' => 'Gentle Scale', 'wave' => 'Letter Wave', 'blur' => 'Soft Blur Reveal' ),
        ) );
        $this->add_control( 'mobile_text_duration', array(
            'label' => 'Mobile Text Enter (ms)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 1600, 'step' => 10 ) ),
            'default' => array( 'size' => 320, 'unit' => 'px' ),
        ) );
        $this->add_control( 'mobile_text_exit', array(
            'label' => 'Mobile Text Exit (ms)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 1600, 'step' => 10 ) ),
            'default' => array( 'size' => 260, 'unit' => 'px' ),
        ) );
        $this->add_control( 'mobile_text_distance', array(
            'label' => 'Mobile Text Movement (px)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 32 ) ),
            'default' => array( 'size' => 2, 'unit' => 'px' ),
        ) );
        $this->add_control( 'mobile_text_scale', array(
            'label' => 'Mobile Text Scale (%)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 100, 'max' => 120 ) ),
            'default' => array( 'size' => 103, 'unit' => 'px' ),
            'condition' => array( 'mobile_text_effect' => 'scale' ),
        ) );
        $this->add_control( 'mobile_text_stagger', array(
            'label' => 'Mobile Letter Stagger (ms)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 150, 'step' => 5 ) ),
            'default' => array( 'size' => 20, 'unit' => 'px' ),
            'condition' => array( 'mobile_text_effect' => 'wave' ),
        ) );
        $this->add_control( 'mobile_text_opacity', array(
            'label' => 'Mobile Start Opacity (%)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 100, 'step' => 5 ) ),
            'default' => array( 'size' => 80, 'unit' => 'px' ),
            'condition' => array( 'mobile_text_effect' => array( 'rise', 'blur' ) ),
        ) );
        $this->add_control( 'mobile_text_ease', array(
            'label' => 'Mobile Text Easing', 'type' => $c::SELECT,
            'default' => 'soft',
            'options' => array( 'soft' => 'Soft', 'smooth' => 'Smooth', 'sine' => 'Gentle', 'back' => 'Light Spring', 'linear' => 'Linear' ),
        ) );
        $this->add_control( 'mobile_misc_heading', array(
            'label' => 'More Mobile Motion', 'type' => $c::HEADING, 'separator' => 'before',
        ) );
        $this->add_control( 'mobile_color_duration', array(
            'label' => 'Text / Icon Color Speed (ms)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 1200, 'step' => 20 ) ),
            'default' => array( 'size' => 300, 'unit' => 'px' ),
        ) );
        $this->add_control( 'mobile_lift', array(
            'label' => 'Mobile Button Lift (px)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 0, 'max' => 24 ) ),
            'default' => array( 'size' => 0, 'unit' => 'px' ),
        ) );
        $this->add_control( 'mobile_scale', array(
            'label' => 'Mobile Button Scale (%)', 'type' => $c::SLIDER,
            'range' => array( 'px' => array( 'min' => 90, 'max' => 120 ) ),
            'default' => array( 'size' => 100, 'unit' => 'px' ),
        ) );
        $this->add_control( 'mobile_ripple', array(
            'label' => 'Mobile Click Ripple', 'type' => $c::SELECT,
            'default' => 'inherit',
            'options' => array( 'inherit' => 'Use existing ripple settings', 'yes' => 'On', 'no' => 'Off' ),
        ) );
        $this->end_controls_section();

        /* ===== INTERACTION ===== */
        $this->start_controls_section( 'advanced_interaction', array( 'label' => 'Accessibility & Behavior', 'tab' => $c::TAB_CONTENT ) );
        $this->add_control( 'custom_css_class', array(
            'label' => 'Extra Button Class', 'type' => $c::TEXT,
            'description' => 'Optional class on the link itself. No leading dot.',
            'default' => '',
        ) );
        $this->end_controls_section();
    }

    /** Safety: allow only known configuration values in HTML data attributes. */
    private function chosen( $value, $valid, $fallback ) {
        return in_array( $value, $valid, true ) ? $value : $fallback;
    }

    private function slider_number( $settings, $key, $default, $min, $max ) {
        $value = isset( $settings[ $key ]['size'] ) && is_numeric( $settings[ $key ]['size'] )
            ? (float) $settings[ $key ]['size'] : $default;
        return max( $min, min( $max, $value ) );
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $label = isset( $s['label'] ) ? (string) $s['label'] : '';
        if ( '' === trim( $label ) ) {
            $label = 'Button';
        }
        $effect = $this->chosen( $s['reveal_effect'] ?? 'directional', array( 'directional', 'left', 'right', 'top', 'bottom', 'center', 'diagonal', 'fade', 'none' ), 'directional' );
        $icon_fx = $this->chosen( $s['icon_effect'] ?? 'rotate', array( 'rotate', 'slide', 'scale', 'pop', 'none' ), 'rotate' );
        $ease = $this->chosen( $s['ease'] ?? 'premium', array( 'premium', 'standard', 'gentle', 'snappy', 'linear' ), 'premium' );
        $shape = $this->chosen( $s['icon_shape'] ?? 'circle', array( 'circle', 'rounded', 'square', 'none' ), 'circle' );
        $position = $this->chosen( $s['icon_position'] ?? 'after', array( 'before', 'after' ), 'after' );
        $text_fx = $this->chosen( $s['text_effect'] ?? 'lift', array( 'none', 'lift', 'rise', 'scale', 'wave', 'blur' ), 'lift' );
        $text_ease = $this->chosen( $s['text_ease'] ?? 'soft', array( 'soft', 'smooth', 'sine', 'back', 'linear' ), 'soft' );
        $has_icon = ! empty( $s['icon']['value'] );
        $has_link = ! empty( $s['link']['url'] );
        $tag = $has_link ? 'a' : 'button';

        $this->add_render_attribute( 'rmb_link', 'class', array( 'rmb-button', 'rmb-button--icon-' . $shape ) );
        $this->add_render_attribute( 'rmb_link', 'data-effect', $effect );
        $this->add_render_attribute( 'rmb_link', 'data-icon-effect', $icon_fx );
        $this->add_render_attribute( 'rmb_link', 'data-text-effect', $text_fx );
        $this->add_render_attribute( 'rmb_link', 'data-text-ease', $text_ease );
        $this->add_render_attribute( 'rmb_link', 'data-text-duration', (string) $this->slider_number( $s, 'text_duration', 420, 0, 1600 ) );
        $this->add_render_attribute( 'rmb_link', 'data-text-exit', (string) $this->slider_number( $s, 'text_exit_duration', 340, 0, 1600 ) );
        $this->add_render_attribute( 'rmb_link', 'data-text-distance', (string) $this->slider_number( $s, 'text_distance', 3, 0, 32 ) );
        $this->add_render_attribute( 'rmb_link', 'data-text-scale', (string) $this->slider_number( $s, 'text_scale', 103, 100, 120 ) );
        $this->add_render_attribute( 'rmb_link', 'data-text-stagger', (string) $this->slider_number( $s, 'text_stagger', 25, 0, 150 ) );
        $this->add_render_attribute( 'rmb_link', 'data-text-opacity', (string) $this->slider_number( $s, 'text_start_opacity', 75, 0, 100 ) );
        $this->add_render_attribute( 'rmb_link', 'data-ease', $ease );
        $this->add_render_attribute( 'rmb_link', 'data-enter', (string) $this->slider_number( $s, 'enter_duration', 700, 0, 2000 ) );
        $this->add_render_attribute( 'rmb_link', 'data-leave', (string) $this->slider_number( $s, 'leave_duration', 620, 0, 2000 ) );
        $this->add_render_attribute( 'rmb_link', 'data-magnetic', ( 'yes' === ( $s['magnetic'] ?? '' ) ) ? 'yes' : 'no' );
        $this->add_render_attribute( 'rmb_link', 'data-magnetic-strength', (string) $this->slider_number( $s, 'magnetic_strength', 5, 0, 20 ) );
        $this->add_render_attribute( 'rmb_link', 'data-ripple', ( 'yes' === ( $s['click_ripple'] ?? '' ) ) ? 'yes' : 'no' );
        $this->add_render_attribute( 'rmb_link', 'data-touch', ( 'yes' === ( $s['mobile_animation'] ?? '' ) ) ? 'yes' : 'no' );
        // Mobile configuration is kept separate from desktop. JS activates it only
        // at the chosen viewport breakpoint (or on touch screens if enabled).
        $this->add_render_attribute( 'rmb_link', 'data-mobile-behavior', $this->chosen( $s['mobile_behavior'] ?? 'tap', array( 'tap', 'press', 'off' ), 'tap' ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-scope', $this->chosen( $s['mobile_scope'] ?? 'phone', array( 'phone', 'touch' ), 'phone' ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-breakpoint', (string) $this->slider_number( $s, 'mobile_breakpoint', 767, 320, 1200 ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-hold', (string) $this->slider_number( $s, 'mobile_hold', 500, 0, 2500 ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-effect', $this->chosen( $s['mobile_reveal_effect'] ?? 'center', array( 'inherit', 'directional', 'left', 'right', 'top', 'bottom', 'center', 'diagonal', 'fade', 'none' ), 'center' ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-enter', (string) $this->slider_number( $s, 'mobile_enter_duration', 420, 0, 2000 ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-leave', (string) $this->slider_number( $s, 'mobile_leave_duration', 360, 0, 2000 ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-ease', $this->chosen( $s['mobile_ease'] ?? 'premium', array( 'premium', 'standard', 'gentle', 'snappy', 'linear' ), 'premium' ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-icon-effect', $this->chosen( $s['mobile_icon_effect'] ?? 'rotate', array( 'inherit', 'rotate', 'slide', 'scale', 'pop', 'none' ), 'rotate' ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-icon-rotation', (string) $this->slider_number( $s, 'mobile_icon_rotation', 90, -360, 360 ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-icon-duration', (string) $this->slider_number( $s, 'mobile_icon_duration', 400, 0, 1800 ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-icon-slide', (string) $this->slider_number( $s, 'mobile_icon_slide', 4, 0, 30 ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-text-effect', $this->chosen( $s['mobile_text_effect'] ?? 'lift', array( 'inherit', 'none', 'lift', 'rise', 'scale', 'wave', 'blur' ), 'lift' ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-text-duration', (string) $this->slider_number( $s, 'mobile_text_duration', 320, 0, 1600 ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-text-exit', (string) $this->slider_number( $s, 'mobile_text_exit', 260, 0, 1600 ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-text-distance', (string) $this->slider_number( $s, 'mobile_text_distance', 2, 0, 32 ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-text-scale', (string) $this->slider_number( $s, 'mobile_text_scale', 103, 100, 120 ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-text-stagger', (string) $this->slider_number( $s, 'mobile_text_stagger', 20, 0, 150 ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-text-opacity', (string) $this->slider_number( $s, 'mobile_text_opacity', 80, 0, 100 ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-text-ease', $this->chosen( $s['mobile_text_ease'] ?? 'soft', array( 'soft', 'smooth', 'sine', 'back', 'linear' ), 'soft' ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-color-duration', (string) $this->slider_number( $s, 'mobile_color_duration', 300, 0, 1200 ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-lift', (string) $this->slider_number( $s, 'mobile_lift', 0, 0, 24 ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-scale', (string) $this->slider_number( $s, 'mobile_scale', 100, 90, 120 ) );
        $this->add_render_attribute( 'rmb_link', 'data-mobile-ripple', $this->chosen( $s['mobile_ripple'] ?? 'inherit', array( 'inherit', 'yes', 'no' ), 'inherit' ) );

        if ( ! empty( $s['custom_css_class'] ) ) {
            $names = preg_split( '/\s+/', (string) $s['custom_css_class'] );
            foreach ( $names as $name ) {
                $name = sanitize_html_class( $name );
                if ( $name ) {
                    $this->add_render_attribute( 'rmb_link', 'class', $name );
                }
            }
        }
        if ( $has_link ) {
            $this->add_link_attributes( 'rmb_link', $s['link'] );
        } else {
            $this->add_render_attribute( 'rmb_link', 'type', 'button' );
        }
        if ( ! empty( $s['aria_label'] ) ) {
            $this->add_render_attribute( 'rmb_link', 'aria-label', sanitize_text_field( $s['aria_label'] ) );
        } elseif ( 'wave' === $text_fx || 'wave' === ( $s['mobile_text_effect'] ?? '' ) ) {
            // Letter animation hides decorative character spans from screen readers.
            $this->add_render_attribute( 'rmb_link', 'aria-label', $label );
        }

        echo '<div class="rmb-wrap">';
        echo '<' . esc_attr( $tag ) . ' ' . $this->get_render_attribute_string( 'rmb_link' ) . '>';
        echo '<span class="rmb-button__reveal" aria-hidden="true"></span>';
        echo '<span class="rmb-button__content">';
        if ( $has_icon && 'before' === $position ) { $this->render_icon_markup( $s['icon'] ); }
        echo '<span class="rmb-button__text">' . esc_html( $label ) . '</span>';
        if ( $has_icon && 'after' === $position ) { $this->render_icon_markup( $s['icon'] ); }
        echo '</span>';
        echo '</' . esc_attr( $tag ) . '>';
        echo '</div>';
    }

    private function render_icon_markup( $icon ) {
        echo '<span class="rmb-button__icon" aria-hidden="true"><span class="rmb-button__glyph">';
        \Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
        echo '</span></span>';
    }
}
