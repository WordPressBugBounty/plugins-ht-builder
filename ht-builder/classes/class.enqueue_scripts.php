<?php

namespace HT_Builder\Elementor;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

 /**
 * Scripts Manager
 */
 class HTBuilder_Scripts{

    private static $instance = null;

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct(){
        $this->init();
    }

    public function init() {

        // Register Scripts
        add_action( 'init', [ $this, 'register_scripts' ] );

        // Frontend Scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );

        // Editor Scripts
        add_action( 'elementor/editor/before_enqueue_scripts', [$this, 'enqueue_editor_scripts'] );

        // Elementor only generates atomic-widget CSS for the main queried post; register
        // this page's header/footer/single/archive templates before that one-shot pass
        // so their atomic styles aren't skipped.
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_atomic_styles_for_templates' ], 15 );
    }

    /**
     * Register the Elementor template IDs used to render the current page's
     * header, footer, and single/archive override with Elementor's atomic-widget
     * CSS pipeline before its own single per-request enqueue pass runs
     * (Frontend::enqueue_styles(), hooked at wp_enqueue_scripts priority 20).
     * That pass only registers is_singular()'s queried post, so a template
     * rendered via HT Builder never gets its atomic CSS generated unless we
     * register it here first.
     */
    public function enqueue_atomic_styles_for_templates() {
        if ( is_admin() || ! class_exists( '\Elementor\Plugin' ) ) {
            return;
        }

        $template_ids = $this->get_active_template_ids();
        if ( empty( $template_ids ) ) {
            return;
        }

        foreach ( $template_ids as $template_id ) {
            do_action( 'elementor/post/render', $template_id );
        }

        \Elementor\Plugin::instance()->frontend->enqueue_styles();
    }

    /**
     * Elementor template IDs HT Builder will render on the current request.
     */
    private function get_active_template_ids() {
        $template_ids = [];

        if ( class_exists( '\HT_Builder\Elementor\HeaderFooter\HTBuilder_Header_Footer' ) ) {
            $header_footer = \HT_Builder\Elementor\HeaderFooter\HTBuilder_Header_Footer::instance();

            if ( ! empty( $header_footer->header_id ) ) {
                $template_ids[] = $header_footer->header_id;
            }
            if ( ! empty( $header_footer->footer_id ) ) {
                $template_ids[] = $header_footer->footer_id;
            }
        }

        if ( class_exists( '\HT_Builder\Elementor\HTBuilder_Custom_Template_Layout' ) ) {
            $template_layout = \HT_Builder\Elementor\HTBuilder_Custom_Template_Layout::instance();

            if ( is_singular( 'post' ) ) {
                $single_tm_id = $template_layout->custom_template_id( 'single_blog_page' );
                if ( ! empty( $single_tm_id ) ) {
                    $template_ids[] = $single_tm_id;
                }
            }

            if ( is_post_type_archive( 'post' ) || htbuilder_is_blog_page() ) {
                $archive_tm_id = $template_layout->custom_template_id( 'archive_blog_page' );
                if ( ! empty( $archive_tm_id ) ) {
                    $template_ids[] = $archive_tm_id;
                }
            }
        }

        return array_unique( array_map( 'absint', $template_ids ) );
    }

    /**
    * Register Scripts
    */

    public function register_scripts(){
        wp_register_script(
            'goodshare',
            HTBUILDER_PL_URL . 'assets/js/goodshare.min.js',
            array('jquery'),
            HTBUILDER_VERSION,
            TRUE
        );
    }

    /**
     * Enqueue frontend scripts
     */
    public function enqueue_frontend_scripts() {

        // CSS
        wp_enqueue_style(
            'htbuilder-main',
            HTBUILDER_PL_URL . 'assets/css/htbuilder.css',
            NULL,
            HTBUILDER_VERSION
        );

        // JS
        wp_enqueue_script( 'masonry' );
        wp_enqueue_script(
            'htbuilder-main',
            HTBUILDER_PL_URL . 'assets/js/htbuilder.js',
            array('jquery'),
            HTBUILDER_VERSION,
            TRUE
        );

    }

    /**
     * Enqueue Editor scripts
     */
    public function enqueue_editor_scripts(){}

}

HTBuilder_Scripts::instance();