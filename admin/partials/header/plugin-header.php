<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$plugin_name = MPPC_PLUGIN_NAME;
global $mppcp_fs;
$version_label = __( 'Free', 'mass-pages-posts-creator' );
$plugin_slug = 'basic_mass_creator';
$plugin_version = 'v' . MPPC_PLUGIN_VERSION;
$current_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$mppc_settings_page = ( isset( $current_page ) && 'mass-pages-posts-creator' === $current_page ? 'active' : '' );
$mppc_getting_started = ( isset( $current_page ) && 'mppc-get-started' === $current_page ? 'active' : '' );
$mppc_account_page = ( isset( $current_page ) && 'mass-pages-posts-creator-account' === $current_page ? 'active' : '' );
$mppc_free_dashboard = ( isset( $current_page ) && 'mppc-upgrade-dashboard' === $current_page ? 'active' : '' );
?>
<div id="dotsstoremain">
    <hr class="wp-header-end" />
    <div class="all-pad">
        <?php 
mppc_get_promotional_bar( $plugin_slug );
?>
        <header class="dots-header">
            <div class="dots-plugin-details">
                <div class="dots-header-left">
                    <div class="dots-logo-main">
                        <img src="<?php 
echo esc_url( MPPC_PLUGIN_URL . 'admin/images/plugin-icon.png' );
?>">
                    </div>
                    <div class="plugin-name">
                        <div class="title"><?php 
esc_html_e( $plugin_name, 'mass-pages-posts-creator' );
?></div>
                    </div>
                    <span class="version-label <?php 
echo esc_attr( $plugin_slug );
?>"><?php 
esc_html_e( $version_label, 'mass-pages-posts-creator' );
?></span>
                    <span class="version-number"><?php 
echo esc_html_e( $plugin_version, 'mass-pages-posts-creator' );
?></span>
                </div>
                <div class="dots-header-right">
                    <div class="button-dots">
                        <a target="_blank" href="<?php 
echo esc_url( 'http://www.thedotstore.com/support/' );
?>"><?php 
esc_html_e( 'Support', 'mass-pages-posts-creator' );
?></a>
                    </div>
                    <div class="button-dots">
                        <a target="_blank" href="<?php 
echo esc_url( 'https://www.thedotstore.com/feature-requests/' );
?>"><?php 
esc_html_e( 'Suggest', 'mass-pages-posts-creator' );
?></a>
                    </div>
                    <div class="button-dots <?php 
echo ( mppcp_fs()->is__premium_only() && mppcp_fs()->can_use_premium_code() ? '' : 'last-link-button' );
?>">
                        <a target="_blank" href="<?php 
echo esc_url( 'https://docs.thedotstore.com/category/271-premium-plugin-settings' );
?>"><?php 
esc_html_e( 'Help', 'mass-pages-posts-creator' );
?></a>
                    </div>
                    <div class="button-dots">
                        <?php 
?>
                            <a class="dots-upgrade-btn" target="_blank" href="javascript:void(0);"><?php 
esc_html_e( 'Upgrade Now', 'mass-pages-posts-creator' );
?></a>
                            <?php 
?>
                    </div>
                </div>
            </div>
            <div class="dots-bottom-menu-main">
                <div class="dots-menu-main">
                    <nav>
                        <ul>
                            <li>
                                <a class="dotstore_plugin <?php 
echo esc_attr( $mppc_settings_page );
?>" href="<?php 
echo esc_url( add_query_arg( array(
    'page' => 'mass-pages-posts-creator',
), admin_url( 'admin.php' ) ) );
?>"><?php 
esc_html_e( 'Bulk Add Pages/Posts', 'mass-pages-posts-creator' );
?></a>
                            </li>
                            <?php 
if ( mppcp_fs()->is__premium_only() && mppcp_fs()->can_use_premium_code() ) {
    ?>
                                <li>
                                    <a class="dotstore_plugin <?php 
    echo esc_attr( $mppc_account_page );
    ?>" href="<?php 
    echo esc_url( $mppcp_fs->get_account_url() );
    ?>"><?php 
    esc_html_e( 'License', 'mass-pages-posts-creator' );
    ?></a>
                                </li>
                                <?php 
} else {
    ?>
                                <li>
                                    <a class="dotstore_plugin dots_get_premium <?php 
    echo esc_attr( $mppc_free_dashboard );
    ?>" href="<?php 
    echo esc_url( add_query_arg( array(
        'page' => 'mppc-upgrade-dashboard',
    ), admin_url( 'admin.php' ) ) );
    ?>"><?php 
    esc_html_e( 'Get Premium', 'mass-pages-posts-creator' );
    ?></a>
                                </li>
                                <?php 
}
?>
                        </ul>
                    </nav>
                </div>
                <div class="dots-getting-started">
                    <a href="<?php 
echo esc_url( add_query_arg( array(
    'page' => 'mppc-get-started',
), admin_url( 'admin.php' ) ) );
?>" class="<?php 
echo esc_attr( $mppc_getting_started );
?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M12 4.75a7.25 7.25 0 100 14.5 7.25 7.25 0 000-14.5zM3.25 12a8.75 8.75 0 1117.5 0 8.75 8.75 0 01-17.5 0zM12 8.75a1.5 1.5 0 01.167 2.99c-.465.052-.917.44-.917 1.01V14h1.5v-.845A3 3 0 109 10.25h1.5a1.5 1.5 0 011.5-1.5zM11.25 15v1.5h1.5V15h-1.5z" fill="#a0a0a0"></path></svg></a>
                </div>
            </div>
        </header>
        <!-- Upgrade to pro popup -->
        <?php 
if ( !(mppcp_fs()->is__premium_only() && mppcp_fs()->can_use_premium_code()) ) {
    require_once MPPC_PLUGIN_PATH . 'admin/partials/dots-upgrade-popup.php';
}
?>
        <div class="dots-settings-inner-main">
            <div class="dots-settings-left-side">
                