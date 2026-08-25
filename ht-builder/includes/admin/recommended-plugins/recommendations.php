<?php
/**
 * Constructor Parameters
 *
 * @param string    $text_domain your plugin text domain.
 * @param string    $parent_menu_slug the menu slug name where the "Recommendations" submenu will appear.
 * @param string    $submenu_label To change the submenu name.
 * @param string    $submenu_page_name an unique page name for the submenu.
 * @param int       $priority Submenu priority adjust.
 * @param string    $hook_suffix use it to load this library assets only to the recommedded plugins page. Not into the whol admin area.
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require(  __DIR__ .'/class.recommended-plugins.php' );

if( class_exists('Hasthemes\HTBuilder\HTRP_Recommended_Plugins') ){
    $recommendations = new Hasthemes\HTBuilder\HTRP_Recommended_Plugins(
        array(
            'text_domain'       => 'ht-builder',
            'parent_menu_slug'  => 'htbuilder',
            'menu_capability'   => 'manage_options',
            'menu_page_slug'    => '',
            'priority'          => '999',
            'assets_url'        => '',
            'hook_suffix'       => 'ht-builder_page_ht-builder_extensions',
        )
    );

    add_action('init', function() use ($recommendations) {
        // Note: this file's own require() already runs on a default-priority
        // 'init' callback (see base.php), so this inner hook needs a distinct,
        // later priority to reliably fire within the same 'init' pass instead
        // of racing the still-iterating default-priority callback list.

        // Only recommend WooCommerce-only plugins (WooLentor) when WooCommerce is installed.
        $woocommerce_active = class_exists( 'WooCommerce' );

        $recommendations->add_new_tab(array(
            'title' => __( 'Recommended Plugins', 'ht-builder' ),
            'active' => true,
            'plugins' => array_merge(
                $woocommerce_active ? array(
                    array(
                        'slug'      => 'woolentor-addons',
                        'location'  => 'woolentor_addons_elementor.php',
                        'name'      => __( 'ShopLentor – WooCommerce Builder for Elementor & Gutenberg +10 Modules – All in One Solution (formerly WooLentor)', 'ht-builder' )
                    ),
                ) : array(),
                array(
                    array(
                        'slug'      => 'ht-mega-for-elementor',
                        'location'  => 'htmega_addons_elementor.php',
                        'name'      => __( 'HT Mega – Absolute Addons for Elementor Page Builder', 'ht-builder' )
                    ),
                    array(
                        'slug'      => 'support-genix-lite',
                        'location'  => 'support-genix-lite.php',
                        'name'      => __( 'Support Genix – Helpdesk, AI Chatbot, Knowledge Base & Customer Support Ticketing System', 'ht-builder' )
                    ),
                    array(
                        'slug'      => 'hashbar-wp-notification-bar',
                        'location'  => 'init.php',
                        'name'      => __( 'Notification Bar for WordPress', 'ht-builder' )
                    ),
                    array(
                        'slug'      => 'wp-plugin-manager',
                        'location'  => 'plugin-main.php',
                        'name'      => __( 'WP Plugin Manager', 'ht-builder' )
                    ),
                    array(
                        'slug'      => 'cookieray',
                        'location'  => 'cookieray.php',
                        'name'      => __( 'CookieRay – Cookie Banner for Cookie Consent (GDPR/CCPA Compliant)', 'ht-builder' )
                    ),
                    array(
                        'slug'      => 'pixelavo',
                        'location'  => 'pixelavo.php',
                        'name'      => __( 'Pixelavo – Server Side Tracking & Pixel + AI Ads Tools', 'ht-builder' )
                    ),
                )
            )
        ));

        $recommendations->add_new_tab(array(
            'title' => esc_html__( 'WooCommerce', 'ht-builder' ),

            'plugins' => array(

                array(
                    'slug'      => 'woolentor-addons',
                    'location'  => 'woolentor_addons_elementor.php',
                    'name'      => __( 'ShopLentor – All-in-One WooCommerce Growth & Store Enhancement Plugin', 'ht-builder' )
                ),
                array(
                    'slug'      => 'whols',
                    'location'  => 'whols.php',
                    'name'      => __( 'Whols – Wholesale Prices and B2B Store Solution for WooCommerce', 'ht-builder' )
                ),
                array(
                    'slug'      => 'recurio',
                    'location'  => 'recurio.php',
                    'name'      => __( 'Recurio – Ultimate Subscription for WooCommerce', 'ht-builder' )
                ),

            )

        ));

        $recommendations->add_new_tab(array(
            'title' => esc_html__( 'Other Plugins', 'ht-builder' ),
            'plugins' => array(
                array(
                    'slug'      => 'ht-slider-for-elementor',
                    'location'  => 'ht-slider-for-elementor.php',
                    'name'      => __( 'HT Slider For Elementor', 'ht-builder' )
                ),
                array(
                    'slug'      => 'ht-menu-lite',
                    'location'  => 'ht-mega-menu.php',
                    'name'      => __( 'HT Menu – Mega Menu Builder for Elementor', 'ht-builder' )
                ),
                array(
                    'slug'      => 'wp-plugin-manager',
                    'location'  => 'plugin-main.php',
                    'name'      => __( 'WP Plugin Manager', 'ht-builder' )
                ),
                array(
                    'slug'      => 'ht-easy-google-analytics',
                    'location'  => 'ht-easy-google-analytics.php',
                    'name'      => __( 'HT Easy GA4 ( Google Analytics 4 )', 'ht-builder' )
                ),
                array(
                    'slug'      => 'ht-contactform',
                    'location'  => 'contact-form-widget-elementor.php',
                    'name'      => __( 'HT Contact Form – Drag & Drop Form Builder for WordPress', 'ht-builder' )
                ),
                array(
                    'slug'      => 'cookieray',
                    'location'  => 'cookieray.php',
                    'name'      => __( 'CookieRay – Cookie Banner for Cookie Consent (GDPR/CCPA Compliant)', 'ht-builder' )
                ),
                array(
                    'slug'      => 'insert-headers-and-footers-script',
                    'location'  => 'init.php',
                    'name'      => __( 'Insert Headers and Footers Code', 'ht-builder' )
                ),
                array(
                    'slug'      => 'courseglade-lms',
                    'location'  => 'courseglade-lms.php',
                    'name'      => __( 'CourseGlade LMS – Online Course & eLearning Platform', 'ht-builder' )
                )

            )
        ));
    }, 20);
}
