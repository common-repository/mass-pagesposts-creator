<?php

/**
 * Plugin Name: Mass Pages/Posts Creator
 * Plugin URI: https://www.thedotstore.com/mass-pages-posts-creator/
 * Description: Mass Pages/Posts Creator is a plugin which provide a simplest interface by which user can create multiple Pages/Posts at a time.
 * Version: 2.2.0
 * Author: theDotstore
 * Author URI: https://www.thedotstore.com
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: mass-pages-posts-creator
 * Domain Path: /languages/
 * 
 * WP tested up to:      6.6.1
 * Requires PHP:         5.6
 * Requires at least:    5.0
 */
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    die;
}
if ( !function_exists( 'mppcp_fs' ) ) {
    // Create a helper function for easy SDK access.
    function mppcp_fs() {
        global $mppcp_fs;
        if ( !isset( $mppcp_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $mppcp_fs = fs_dynamic_init( array(
                'id'              => '3481',
                'slug'            => 'mass-pages-posts-creator',
                'type'            => 'plugin',
                'public_key'      => 'pk_d515579f040a86a51afd9f721dfed',
                'is_premium'      => false,
                'premium_suffix'  => 'Premium',
                'has_addons'      => false,
                'has_paid_plans'  => true,
                'has_affiliation' => 'selected',
                'menu'            => array(
                    'slug'       => 'mass-pages-posts-creator',
                    'first-path' => 'admin.php?page=mass-pages-posts-creator',
                    'contact'    => false,
                    'support'    => false,
                ),
                'is_live'         => true,
            ) );
        }
        return $mppcp_fs;
    }

    // Init Freemius.
    mppcp_fs();
    // Signal that SDK was initiated.
    do_action( 'mppcp_fs_loaded' );
}
if ( !defined( 'MPPC_PLUGIN_VERSION' ) ) {
    define( 'MPPC_PLUGIN_VERSION', '2.2.0' );
}
if ( !defined( 'MPPC_PLUGIN_URL' ) ) {
    define( 'MPPC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'MPPC_PLUGIN_PATH' ) ) {
    define( 'MPPC_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}
if ( !defined( 'MPPC_PLUGIN_NAME' ) ) {
    define( 'MPPC_PLUGIN_NAME', __( 'Mass Pages/Posts Creator', 'mass-pages-posts-creator' ) );
}
if ( !defined( 'MPPC_STORE_URL' ) ) {
    define( 'MPPC_STORE_URL', 'https://www.thedotstore.com/' );
}
// Call plugin's general functions file
require plugin_dir_path( __FILE__ ) . 'includes/mass-pages-posts-creator-functions.php';
// Load plugin styles and scripts
$menu_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS );
if ( isset( $menu_page ) && !empty( $menu_page ) && (strpos( $menu_page, 'mass-pages-posts-creator' ) !== false || strpos( $menu_page, 'mppc' ) !== false) ) {
    add_action( 'admin_enqueue_scripts', 'mpc_load_my_script' );
    add_action( 'admin_enqueue_scripts', 'mpc_styles' );
    add_filter( 'admin_footer_text', 'mppc_admin_footer_review' );
}
if ( !function_exists( 'mpc_load_my_script' ) ) {
    function mpc_load_my_script() {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-dialog' );
        wp_enqueue_script(
            'mppc-select2-jquery',
            plugin_dir_url( __FILE__ ) . 'js/select2.min.js',
            array('jquery'),
            MPPC_PLUGIN_VERSION,
            false
        );
        wp_enqueue_script(
            'mppc-help-scout-beacon',
            plugin_dir_url( __FILE__ ) . 'js/help-scout-beacon.js',
            array(),
            MPPC_PLUGIN_VERSION,
            'all'
        );
        wp_enqueue_script(
            'mppc-freemius_pro',
            'https://checkout.freemius.com/checkout.min.js',
            array('jquery'),
            MPPC_PLUGIN_VERSION,
            'all'
        );
        wp_enqueue_script(
            'mppc-custom-js',
            plugin_dir_url( __FILE__ ) . 'js/custom.js',
            array(),
            MPPC_PLUGIN_VERSION,
            'all'
        );
        wp_localize_script( 'mppc-custom-js', 'adminajax', array(
            'ajaxurl'                 => admin_url( 'admin-ajax.php' ),
            'ajax_icon'               => plugin_dir_url( __FILE__ ) . '/admin/images/ajax-loader.gif',
            'dpb_api_url'             => MPPC_STORE_URL,
            'setup_wizard_ajax_nonce' => wp_create_nonce( 'wizard_ajax_nonce' ),
        ) );
    }

}
add_action( 'admin_init', 'mpc_welcome_mass_page_creator_screen_do_activation_redirect' );
add_action( 'wp_ajax_page_finder_ajax', 'mppc_page_finder_ajax' );
if ( !function_exists( 'convert_array_to_json' ) ) {
    function convert_array_to_json(  $arr  ) {
        $filter_data = [];
        foreach ( $arr as $key => $value ) {
            $option = [];
            $option['name'] = $value;
            $option['attributes']['value'] = $key;
            $filter_data[] = $option;
        }
        return $filter_data;
    }

}
if ( !function_exists( 'mppc_page_finder_ajax' ) ) {
    function mppc_page_finder_ajax() {
        // Verify nonce
        check_ajax_referer( 'mass_pages_posts_creator_nonce', 'security' );
        // List pages
        $json = true;
        $request_value = filter_input( INPUT_GET, 'value', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_value = ( isset( $request_value ) ? sanitize_text_field( $request_value ) : '' );
        $query = new WP_Query(array(
            'post_parent' => 0,
            'post_type'   => "page",
            'post_status' => 'publish',
            's'           => $post_value,
            'showposts'   => -1,
        ));
        $parent_pages_num = $query->found_posts;
        $options = [];
        $html = '';
        if ( $parent_pages_num > 0 ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $html .= '<option value="' . esc_attr( $query->post->ID ) . '">' . '#' . esc_html( $query->post->ID ) . ' - ' . esc_html( get_the_title( $query->post->ID ) ) . '</option>';
                $options[] = array($query->post->ID, esc_html( $query->post->post_title ));
            }
        }
        if ( $json ) {
            echo wp_json_encode( $options );
            wp_die();
        }
        echo wp_kses( $html, mppc_allowed_html_tags() );
        wp_die();
    }

}
if ( !function_exists( 'mpc_welcome_mass_page_creator_screen_do_activation_redirect' ) ) {
    function mpc_welcome_mass_page_creator_screen_do_activation_redirect() {
        if ( !get_transient( '_mass_page_post_creator_welcome_screen' ) ) {
            return;
        }
        // Delete the redirect transient
        delete_transient( '_mass_page_post_creator_welcome_screen' );
        // if activating from network, or bulk
        $is_activate = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( is_network_admin() || isset( $is_activate ) ) {
            return;
        }
        // Redirect to extra cost welcome  page
        wp_safe_redirect( add_query_arg( array(
            'page' => 'mass-pages-posts-creator',
        ), admin_url( 'admin.php' ) ) );
        exit;
    }

}
if ( !function_exists( 'mpc_styles' ) ) {
    function mpc_styles() {
        wp_enqueue_style(
            'mppc-select2-min-style',
            plugin_dir_url( __FILE__ ) . 'admin/css/select2.min.css',
            array(),
            MPPC_PLUGIN_VERSION,
            'all'
        );
        wp_enqueue_style(
            'mppc-jquery-ui-min-style',
            plugin_dir_url( __FILE__ ) . 'admin/css/jquery-ui.min.css',
            array(),
            MPPC_PLUGIN_VERSION,
            'all'
        );
        wp_enqueue_style(
            'mppc-jquery-timepicker-style',
            plugin_dir_url( __FILE__ ) . 'admin/css/jquery.timepicker.min.css',
            array(),
            MPPC_PLUGIN_VERSION,
            'all'
        );
        wp_enqueue_style(
            'mppc-font-awesome-style',
            plugin_dir_url( __FILE__ ) . 'admin/css/font-awesome.min.css',
            array(),
            MPPC_PLUGIN_VERSION,
            'all'
        );
        wp_enqueue_style(
            'mppc-style-css',
            plugin_dir_url( __FILE__ ) . 'css/style.css',
            array('wp-jquery-ui-dialog'),
            MPPC_PLUGIN_VERSION,
            'all'
        );
        wp_enqueue_style(
            'mppc-main-style',
            plugin_dir_url( __FILE__ ) . 'admin/css/style.css',
            array(),
            'all'
        );
        wp_enqueue_style(
            'mppc-media-style',
            plugin_dir_url( __FILE__ ) . 'admin/css/media.css',
            array(),
            MPPC_PLUGIN_VERSION,
            'all'
        );
        wp_enqueue_style(
            'mppc-plugin-new-style',
            plugin_dir_url( __FILE__ ) . 'admin/css/plugin-new-style.css',
            array(),
            MPPC_PLUGIN_VERSION,
            'all'
        );
        wp_enqueue_style(
            'mppc-plugin-setup-wizard',
            plugin_dir_url( __FILE__ ) . 'admin/css/plugin-setup-wizard.css',
            array(),
            MPPC_PLUGIN_VERSION,
            'all'
        );
        if ( !(mppcp_fs()->is__premium_only() && mppcp_fs()->can_use_premium_code()) ) {
            wp_enqueue_style(
                'mppc-plugin-upgrade-dashboard',
                plugin_dir_url( __FILE__ ) . 'admin/css/upgrade-dashboard.css',
                array(),
                MPPC_PLUGIN_VERSION,
                'all'
            );
        }
    }

}
if ( !function_exists( 'mpc_pages_posts_creator' ) ) {
    function mpc_pages_posts_creator() {
        global $GLOBALS;
        if ( empty( $GLOBALS['admin_page_hooks']['dots_store'] ) ) {
            add_menu_page(
                'Dotstore Plugins',
                __( 'Dotstore Plugins', 'mass-pages-posts-creator' ),
                'null',
                'dots_store',
                'dot_store_menu_page',
                'dashicons-marker',
                25
            );
        }
        add_submenu_page(
            'dots_store',
            'Mass Pages/Posts Creator',
            __( 'Mass Pages/Posts Creator', 'mass-pages-posts-creator' ),
            'manage_options',
            'mass-pages-posts-creator',
            'mppc_admin_settings_page'
        );
        add_submenu_page(
            'dots_store',
            'Getting Started',
            __( 'Getting Started', 'mass-pages-posts-creator' ),
            'manage_options',
            'mppc-get-started',
            'mppc_get_started_page'
        );
        add_submenu_page(
            'dots_store',
            'Get Premium',
            __( 'Get Premium', 'mass-pages-posts-creator' ),
            'manage_options',
            'mppc-upgrade-dashboard',
            'mppc_get_premium_page'
        );
    }

}
if ( !function_exists( 'mppc_remove_admin_submenus' ) ) {
    function mppc_remove_admin_submenus() {
        // Remove submenus
        remove_submenu_page( 'dots_store', 'dots_store' );
        remove_submenu_page( 'dots_store', 'mppc-get-started' );
        remove_submenu_page( 'dots_store', 'mppc-upgrade-dashboard' );
        // Dotstore menu icon css
        echo '<style>
            .toplevel_page_dots_store .dashicons-marker::after{content:"";border:3px solid;position:absolute;top:14px;left:15px;border-radius:50%;opacity: 0.6;}
            li.toplevel_page_dots_store:hover .dashicons-marker::after,li.toplevel_page_dots_store.current .dashicons-marker::after{opacity: 1;}
            @media only screen and (max-width: 960px){
                .toplevel_page_dots_store .dashicons-marker::after{left:14px;}
            }
            </style>';
    }

}
/**
 * Quick guide page
 *
 * @since    1.0.0
 */
if ( !function_exists( 'mppc_get_started_page' ) ) {
    function mppc_get_started_page() {
        require_once plugin_dir_path( __FILE__ ) . 'admin/partials/mppc-get-started-page.php';
    }

}
/**
 * Get premium page
 *
 * @since    2.2.0
 */
if ( !function_exists( 'mppc_get_premium_page' ) ) {
    function mppc_get_premium_page() {
        require_once plugin_dir_path( __FILE__ ) . 'admin/partials/dots-upgrade-dashboard.php';
    }

}
/**
 * Plugin information page
 *
 * @since    1.0.0
 */
if ( !function_exists( 'mppc_admin_settings_page' ) ) {
    function mppc_admin_settings_page() {
        require_once plugin_dir_path( __FILE__ ) . 'admin/partials/mppc-admin-settings-page.php';
    }

}
if ( !function_exists( 'mpc_ajax_action' ) ) {
    function mpc_ajax_action() {
        // Verify nonce
        check_ajax_referer( 'mass_pages_posts_creator_nonce', 'security' );
        // Create mass posts/pages
        $prefix_word = filter_input( INPUT_POST, 'prefix_word', FILTER_SANITIZE_SPECIAL_CHARS );
        $postfix_word = filter_input( INPUT_POST, 'postfix_word', FILTER_SANITIZE_SPECIAL_CHARS );
        $pages_content = filter_input( INPUT_POST, 'pages_content', FILTER_SANITIZE_SPECIAL_CHARS );
        $parent_page_id = filter_input( INPUT_POST, 'parent_page_id', FILTER_SANITIZE_SPECIAL_CHARS );
        $template_name = filter_input( INPUT_POST, 'template_name', FILTER_SANITIZE_SPECIAL_CHARS );
        $type = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS );
        $page_status = filter_input( INPUT_POST, 'page_status', FILTER_SANITIZE_SPECIAL_CHARS );
        $authors = filter_input( INPUT_POST, 'authors', FILTER_SANITIZE_SPECIAL_CHARS );
        $excerpt_content = filter_input( INPUT_POST, 'excerpt_content', FILTER_SANITIZE_SPECIAL_CHARS );
        $no_post_add = filter_input( INPUT_POST, 'no_post_add', FILTER_SANITIZE_SPECIAL_CHARS );
        $comment_status = filter_input( INPUT_POST, 'comment_status', FILTER_SANITIZE_SPECIAL_CHARS );
        $pages_list = filter_input( INPUT_POST, 'pages_list', FILTER_SANITIZE_SPECIAL_CHARS );
        $prefix_word = sanitize_text_field( wp_unslash( $prefix_word ) );
        $postfix_word = sanitize_text_field( wp_unslash( $postfix_word ) );
        $pages_content = htmlspecialchars_decode( $pages_content );
        $parent_page_id = sanitize_text_field( wp_unslash( $parent_page_id ) );
        $template_name = sanitize_text_field( wp_unslash( $template_name ) );
        $type = sanitize_text_field( wp_unslash( $type ) );
        $page_status = sanitize_text_field( wp_unslash( $page_status ) );
        $authors = sanitize_text_field( wp_unslash( $authors ) );
        $excerpt_content = sanitize_textarea_field( $excerpt_content );
        $no_post_add = sanitize_text_field( wp_unslash( $no_post_add ) );
        $comment_status = sanitize_text_field( wp_unslash( $comment_status ) );
        $pages_list = sanitize_textarea_field( $pages_list );
        $page_list = explode( ",", $pages_list );
        if ( $no_post_add === '' ) {
            $no_post_count = 1;
        } else {
            $no_post_count = $no_post_add;
        }
        $responsedata = [];
        foreach ( range( 1, $no_post_count ) as $i ) {
            foreach ( $page_list as $page_name ) {
                $my_post = array(
                    'post_title'     => $prefix_word . ' ' . $page_name . ' ' . $postfix_word,
                    'post_type'      => $type,
                    'post_content'   => $pages_content,
                    'post_author'    => $authors,
                    'post_parent'    => $parent_page_id,
                    'post_status'    => $page_status,
                    'post_excerpt'   => $excerpt_content,
                    'comment_status' => $comment_status,
                );
                $last_insert_id = wp_insert_post( $my_post );
                update_post_meta( $last_insert_id, 'post_number', $i );
                if ( 'draft' === $page_status ) {
                    $url = get_permalink( $last_insert_id ) . '&preview=true';
                } else {
                    if ( 'auto-draft' === $page_status ) {
                        $url = '-';
                    } else {
                        $url = get_permalink( $last_insert_id );
                    }
                }
                $data = [];
                $data['id'] = esc_html( $last_insert_id );
                $data['pagename'] = esc_html( $page_name );
                $data['status'] = esc_html( "Ok" );
                if ( 'auto-draft' === $page_status || 'trash' === $page_status ) {
                    $data['url'] = __( "-", 'mass-pages-posts-creator' );
                } else {
                    $data['url'] = $url;
                }
                $responsedata[] = $data;
                add_post_meta( $last_insert_id, '_wp_page_template', $template_name );
            }
        }
        echo wp_json_encode( $responsedata );
        wp_die();
    }

}
add_action( 'wp_ajax_mpc_ajax_action', 'mpc_ajax_action' );
add_action( 'wp_ajax_nopriv_mpc_ajax_action', 'mpc_ajax_action' );
if ( !function_exists( 'mpc_activate' ) ) {
    function mpc_activate() {
        set_transient( '_mass_page_post_creator_welcome_screen', true, 30 );
    }

}
register_activation_hook( __FILE__, 'mpc_activate' );
if ( !function_exists( 'mpc_deactivate' ) ) {
    function mpc_deactivate() {
    }

}
register_deactivation_hook( __FILE__, 'mpc_deactivate' );
if ( !function_exists( 'mppc_admin_footer_review' ) ) {
    function mppc_admin_footer_review() {
        $url = '';
        $url = esc_url( 'https://wordpress.org/plugins/mass-pagesposts-creator/#reviews' );
        $html = sprintf( wp_kses( __( '<strong>We need your support</strong> to keep updating and improving the plugin. Please <a href="%1$s" target="_blank">help us by leaving a good review</a> :) Thanks!', 'mass-pages-posts-creator' ), array(
            'strong' => array(),
            'a'      => array(
                'href'   => array(),
                'target' => 'blank',
            ),
        ) ), esc_url( $url ) );
        return wp_kses_post( $html );
    }

}
/**
 * Check Initialize plugin in case of WooCommerce plugin is missing.
 *
 * @since    1.0.0
 */
if ( !function_exists( 'mass_page_post_creator_initialize_plugin' ) ) {
    function mass_page_post_creator_initialize_plugin() {
        add_action( 'admin_menu', 'mpc_pages_posts_creator' );
        add_action( 'admin_head', 'mppc_remove_admin_submenus' );
        // Load the plugin text domain for translation.
        load_plugin_textdomain( 'mass-pages-posts-creator', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

}
add_action( 'plugins_loaded', 'mass_page_post_creator_initialize_plugin' );