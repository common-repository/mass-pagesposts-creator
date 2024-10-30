<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
?>
	<div class="mmqw-section-left thedotstore-main-table res-cl">
		<div class="dots-getting-started-main">
	        <div class="getting-started-content">
	            <span><?php esc_html_e( 'How to Get Started', 'mass-pages-posts-creator' ); ?></span>
	            <h3><?php esc_html_e( 'Welcome to Mass Pages/Posts Creator Plugin', 'mass-pages-posts-creator' ); ?></h3>
	            <p><?php esc_html_e( 'Thank you for choosing our top-rated Mass Pages/Posts Creator plugin. Our user-friendly interface makes it easy to create mass pages, posts, and other customized content.', 'mass-pages-posts-creator' ); ?></p>
	            <p>
	                <?php 
	                echo sprintf(
	                    esc_html__('To help you get started, watch the quick tour video on the right. For more help, explore our help documents or visit our %s for detailed video tutorials.', 'mass-pages-posts-creator'),
	                    '<a href="' . esc_url('https://www.youtube.com/@Dotstore16') . '" target="_blank">' . esc_html__('YouTube channel', 'mass-pages-posts-creator') . '</a>',
	                );
	                ?>
	            </p>
	            <div class="getting-started-actions">
	                <a href="<?php echo esc_url(add_query_arg(array('page' => 'mass-pages-posts-creator'), admin_url('admin.php'))); ?>" class="quick-start"><?php esc_html_e( 'Create Mass Pages/Posts', 'mass-pages-posts-creator' ); ?><span class="dashicons dashicons-arrow-right-alt"></span></a>
	                <a href="https://docs.thedotstore.com/article/964-beginners-guide-for-mass-pages-posts-creator" target="_blank" class="setup-guide"><span class="dashicons dashicons-book-alt"></span><?php esc_html_e( 'Read the Setup Guide', 'mass-pages-posts-creator' ); ?></a>
	            </div>
	        </div>
	        <div class="getting-started-video">
	            <iframe width="960" height="600" src="<?php echo esc_url('https://www.youtube.com/embed/32LfqNONyNI'); ?>" title="<?php esc_attr_e( 'Plugin Tour', 'mass-pages-posts-creator' ); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	        </div>
	    </div>
	</div>

<?php
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-footer.php' );