<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );

// Get product details from Freemius via API
$annual_plugin_price = '';
$monthly_plugin_price = '';
$plugin_details = array(
    'product_id' => 45355,
);

$api_url = add_query_arg(wp_rand(), '', MPPC_STORE_URL . 'wp-json/dotstore-product-fs-data/v2/dotstore-product-fs-data');
$final_api_url = add_query_arg($plugin_details, $api_url);

if ( function_exists( 'vip_safe_wp_remote_get' ) ) {
    $api_response = vip_safe_wp_remote_get( $final_api_url, 3, 1, 20 );
} else {
    $api_response = wp_remote_get( $final_api_url ); // phpcs:ignore
}

if ( ( !is_wp_error($api_response)) && (200 === wp_remote_retrieve_response_code( $api_response ) ) ) {
	$api_response_body = wp_remote_retrieve_body($api_response);
	$plugin_pricing = json_decode( $api_response_body, true );

	if ( isset( $plugin_pricing ) && ! empty( $plugin_pricing ) ) {
		$first_element = reset( $plugin_pricing );
        if ( ! empty( $first_element['price_data'] ) ) {
            $first_price = reset( $first_element['price_data'] )['annual_price'];
        } else {
            $first_price = "0";
        }

        if( "0" !== $first_price ){
        	$annual_plugin_price = $first_price;
        	$monthly_plugin_price = round( intval( $first_price  ) / 12 );
        }
	}
}

// Set plugin key features content
$plugin_key_features = array(
    array(
        'title' => esc_html__( 'Instant Page and Posts Generator', 'mass-pages-posts-creator' ),
        'description' => esc_html__( 'Add titles, count to generate, select the type, add content, and with one click, you are ready to create mass pages or posts.', 'mass-pages-posts-creator' ),
        'popup_image' => esc_url( MPPC_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-one-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Generating many pages or posts for your WordPress site has never been easier. With just a few simple steps, you can create multiple entries in one time.', 'mass-pages-posts-creator' )
        ),
        'popup_examples' => array(
            esc_html__( 'Generate all your specified pages or posts with a single click. The tool automates the process, saving you valuable time and effort.', 'mass-pages-posts-creator' ),
            esc_html__( 'For example, if you\'re launching a new store or news media website, you can quickly create individual pages with descriptions, title, and content.', 'mass-pages-posts-creator' ),
        )
    ),
    array(
        'title' => esc_html__( 'Select Pre-defined and Custom Templates', 'mass-pages-posts-creator' ),
        'description' => esc_html__( 'You can select the templates before generating the pages/posts across different layouts, including custom page templates.', 'mass-pages-posts-creator' ),
        'popup_image' => esc_url( MPPC_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-two-img.png' ),
        'popup_content' => array(
        	esc_html__( 'With just a few simple steps, you can create multiple entries quickly with default or predefined templates.', 'mass-pages-posts-creator' ),
        ),
        'popup_examples' => array(
            esc_html__( 'For example, you could add titles like "Summer Sale Products," "Winter Collection," or "New Arrivals." Next, choose the number of pages or posts you need. Next, select the custom template that was created for new seasonal products.', 'mass-pages-posts-creator' ),
        )
    ),
    array(
        'title' => esc_html__( 'Default Status Before Generate Posts', 'mass-pages-posts-creator' ),
        'description' => esc_html__( 'Preemptively set page and post statuses before generating them in mass, such as Publish, Draft, Pending, Future, Private, Trash, etc.', 'mass-pages-posts-creator' ),
        'popup_image' => esc_url( MPPC_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-three-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Enhance your content management workflow by preemptively setting default statuses for pages and posts before generating them in bulk. ', 'mass-pages-posts-creator' ),
        ),
        'popup_examples' => array(
            esc_html__( 'You can assign statuses such as Publish, Draft, Pending, Future, Private, Trash, and more, ensuring that your content is appropriately categorized and managed immediately.', 'mass-pages-posts-creator' ),
            esc_html__( 'For instance, if you are preparing titles and some default content for your news website but need a content writer to complete the final touches, you can generate posts with the Draft status.', 'mass-pages-posts-creator' )
        )
    ),
    array(
        'title' => esc_html__( 'Any Post Type Selection', 'mass-pages-posts-creator' ),
        'description' => esc_html__( 'Easily generate Pages, Posts, Products, or any custom post types used on your website to fulfill your unique needs.', 'mass-pages-posts-creator' ),
        'popup_image' => esc_url( MPPC_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-four-img.png' ),
        'popup_content' => array(
        	esc_html__( 'This feature supports a wide range of content types, providing the flexibility to quickly populate your site with the necessary content, whether you\'re building a blog, an e-commerce platform, or a custom content management system.', 'mass-pages-posts-creator' ),
        ),
        'popup_examples' => array(
            esc_html__( 'For example, if you\'re setting up an online newspaper, you can efficiently create multiple posts listings, complete with titles, descriptions, and categories, saving significant time and effort.', 'mass-pages-posts-creator' ),
            esc_html__( 'Similarly, for a blog, you can bulk-generate posts with placeholder content that is ready for detailed editing.', 'mass-pages-posts-creator' ),
        )
    ),
    array(
        'title' => esc_html__( 'Select Post/Page Author', 'mass-pages-posts-creator' ),
        'description' => esc_html__( 'Easily generate posts or pages in bulk on your website, with the flexibility to assign your desired post or page author.', 'mass-pages-posts-creator' ),
        'popup_image' => esc_url( MPPC_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-five-img.png' ),
        'popup_content' => array(
        	esc_html__( 'This feature is especially useful for multi-author websites, allowing you to attribute content to the appropriate author seamlessly.', 'mass-pages-posts-creator' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Whether you\'re managing a blog, news site, or collaborative project, you can ensure that each generated post or page is correctly attributed to a specific author.', 'mass-pages-posts-creator' ),
            esc_html__( 'For instance, if you\'re running a blog with multiple contributors, you can efficiently assign bulk-generated posts to the relevant authors.', 'mass-pages-posts-creator' ),
        )
    ),
    array(
        'title' => esc_html__( 'Select Post/Page Comment Status', 'mass-pages-posts-creator' ),
        'description' => esc_html__( 'Easily decide and set the comment status for posts or pages while generating them in bulk.', 'mass-pages-posts-creator' ),
        'popup_image' => esc_url( MPPC_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-six-img.png' ),
        'popup_content' => array(
        	esc_html__( 'This feature allows you to control the engagement level of your content by selecting the appropriate comment status—whether it\'s open, closed – during bulk creation.', 'mass-pages-posts-creator' ),
        ),
        'popup_examples' => array(
            esc_html__( 'This is particularly useful for managing community interaction on a large scale, ensuring that your posts or pages have the desired commenting rules from the moment they\'re published.', 'mass-pages-posts-creator' ),
            esc_html__( 'For example, if you\'re generating a series of informational pages where feedback is not needed, you can preemptively close comments.', 'mass-pages-posts-creator' ),
        )
    ),
);
?>
	<div class="dotstore-upgrade-dashboard">
		<div class="premium-benefits-section">
			<h2><?php esc_html_e( 'Upgrade to Unlock Premium Features', 'mass-pages-posts-creator' ); ?></h2>
			<p><?php esc_html_e( 'Upgrade to the premium version for advanced features and streamline your content management with mass creation.', 'mass-pages-posts-creator' ); ?></p>
		</div>
		<div class="premium-plugin-details">
			<div class="premium-key-fetures">
				<h3><?php esc_html_e( 'Discover Our Top Key Features', 'mass-pages-posts-creator' ); ?></h3>
				<ul>
					<?php 
					if ( isset( $plugin_key_features ) && ! empty( $plugin_key_features ) ) {
						foreach( $plugin_key_features as $key_feature ) {
							?>
							<li>
								<h4><?php echo esc_html( $key_feature['title'] ); ?><span class="premium-feature-popup"></span></h4>
								<p><?php echo esc_html( $key_feature['description'] ); ?></p>
								<div class="feature-explanation-popup-main">
									<div class="feature-explanation-popup-outer">
										<div class="feature-explanation-popup-inner">
											<div class="feature-explanation-popup">
												<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'mass-pages-posts-creator'); ?>"></span>
												<div class="popup-body-content">
													<div class="feature-content">
														<h4><?php echo esc_html( $key_feature['title'] ); ?></h4>
														<?php 
														if ( isset( $key_feature['popup_content'] ) && ! empty( $key_feature['popup_content'] ) ) {
															foreach( $key_feature['popup_content'] as $feature_content ) {
																?>
																<p><?php echo esc_html( $feature_content ); ?></p>
																<?php
															}
														}
														?>
														<ul>
															<?php 
															if ( isset( $key_feature['popup_examples'] ) && ! empty( $key_feature['popup_examples'] ) ) {
																foreach( $key_feature['popup_examples'] as $feature_example ) {
																	?>
																	<li><?php echo esc_html( $feature_example ); ?></li>
																	<?php
																}
															}
															?>
														</ul>
													</div>
													<div class="feature-image">
														<img src="<?php echo esc_url( $key_feature['popup_image'] ); ?>" alt="<?php echo esc_attr( $key_feature['title'] ); ?>">
													</div>
												</div>
											</div>		
										</div>
									</div>
								</div>
							</li>
							<?php
						}
					}
					?>
				</ul>
			</div>
			<div class="premium-plugin-buy">
				<div class="premium-buy-price-box">
					<div class="price-box-top">
						<div class="pricing-icon">
							<img src="<?php echo esc_url( MPPC_PLUGIN_URL . 'admin/images/premium-upgrade-img/pricing-1.svg' ); ?>" alt="<?php esc_attr_e( 'Personal Plan', 'mass-pages-posts-creator' ); ?>">
						</div>
						<h4><?php esc_html_e( 'Personal', 'mass-pages-posts-creator' ); ?></h4>
					</div>
					<div class="price-box-middle">
						<?php
						if ( ! empty( $annual_plugin_price ) ) {
							?>
							<div class="monthly-price-wrap"><?php echo esc_html( '$' . $monthly_plugin_price ); ?><span class="seprater">/</span><span><?php esc_html_e( 'month', 'mass-pages-posts-creator' ); ?></span></div>
							<div class="yearly-price-wrap"><?php echo sprintf( esc_html__( 'Pay $%s today. Renews in 12 months.', 'mass-pages-posts-creator' ), esc_html( $annual_plugin_price ) ); ?></div>
							<?php	
						}
						?>
						<span class="for-site"><?php esc_html_e( '1 site', 'mass-pages-posts-creator' ); ?></span>
						<p class="price-desc"><?php esc_html_e( 'Great for website owners with a single WordPress Site', 'mass-pages-posts-creator' ); ?></p>
					</div>
					<div class="price-box-bottom">
						<a href="javascript:void(0);" class="upgrade-now"><?php esc_html_e( 'Get The Premium Version', 'mass-pages-posts-creator' ); ?></a>
						<p class="trusted-by"><?php esc_html_e( 'Trusted by 100,000+ store owners and WP experts!', 'mass-pages-posts-creator' ); ?></p>
					</div>
				</div>
				<div class="premium-satisfaction-guarantee premium-satisfaction-guarantee-2">
					<div class="money-back-img">
						<img src="<?php echo esc_url(MPPC_PLUGIN_URL . 'admin/images/premium-upgrade-img/14-Days-Money-Back-Guarantee.png'); ?>" alt="<?php esc_attr_e('14-Day money-back guarantee', 'mass-pages-posts-creator'); ?>">
					</div>
					<div class="money-back-content">
						<h2><?php esc_html_e( '14-Day Satisfaction Guarantee', 'mass-pages-posts-creator' ); ?></h2>
						<p><?php esc_html_e( 'You are fully protected by our 100% Satisfaction Guarantee. If over the next 14 days you are unhappy with our plugin or have an issue that we are unable to resolve, we\'ll happily consider offering a 100% refund of your money.', 'mass-pages-posts-creator' ); ?></p>
					</div>
				</div>
				<div class="plugin-customer-review">
					<h3><?php esc_html_e( 'Amazing plugin and Excellent support!', 'mass-pages-posts-creator' ); ?></h3>
					<p>
						<?php echo wp_kses( __( 'Excellent product, <strong>it facilitates the creation of pages in a few seconds</strong>. Finally found a plugin that meets my wishes! Thanks for this amazing plugin. <strong>100% recommendable</strong>.', 'mass-pages-posts-creator' ), array(
				                'strong' => array(),
				            ) ); 
			            ?>
		            </p>
					<div class="review-customer">
						<div class="customer-img">
							<img src="<?php echo esc_url(MPPC_PLUGIN_URL . 'admin/images/premium-upgrade-img/customer-profile-img.jpeg'); ?>" alt="<?php esc_attr_e('Customer Profile Image', 'mass-pages-posts-creator'); ?>">
						</div>
						<div class="customer-name">
							<span><?php esc_html_e( 'Ferran Harris', 'mass-pages-posts-creator' ); ?></span>
							<div class="customer-rating-bottom">
								<div class="customer-ratings">
									<span class="dashicons dashicons-star-filled"></span>
									<span class="dashicons dashicons-star-filled"></span>
									<span class="dashicons dashicons-star-filled"></span>
									<span class="dashicons dashicons-star-filled"></span>
									<span class="dashicons dashicons-star-filled"></span>
								</div>
								<div class="verified-customer">
									<span class="dashicons dashicons-yes-alt"></span>
									<?php esc_html_e( 'Verified Customer', 'mass-pages-posts-creator' ); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="upgrade-to-pro-faqs">
			<h2><?php esc_html_e( 'FAQs', 'mass-pages-posts-creator' ); ?></h2>
			<div class="upgrade-faqs-main">
				<div class="upgrade-faqs-list">
					<div class="upgrade-faqs-header">
						<h3><?php esc_html_e( 'Do you offer support for the plugin? What’s it like?', 'mass-pages-posts-creator' ); ?></h3>
					</div>
					<div class="upgrade-faqs-body">
						<p>
						<?php 
							echo sprintf(
							    esc_html__('Yes! You can read our %s or submit a %s. We are very responsive and strive to do our best to help you.', 'mass-pages-posts-creator'),
							    '<a href="' . esc_url('https://docs.thedotstore.com/collection/266-mass-pages-posts-creator') . '" target="_blank">' . esc_html__('knowledge base', 'mass-pages-posts-creator') . '</a>',
							    '<a href="' . esc_url('https://www.thedotstore.com/support-ticket/') . '" target="_blank">' . esc_html__('support ticket', 'mass-pages-posts-creator') . '</a>',
							);

						?>
						</p>
					</div>
				</div>
				<div class="upgrade-faqs-list">
					<div class="upgrade-faqs-header">
						<h3><?php esc_html_e( 'What payment methods do you accept?', 'mass-pages-posts-creator' ); ?></h3>
					</div>
					<div class="upgrade-faqs-body">
						<p><?php esc_html_e( 'You can pay with your credit card using Stripe checkout. Or your PayPal account.', 'mass-pages-posts-creator' ); ?></p>
					</div>
				</div>
				<div class="upgrade-faqs-list">
					<div class="upgrade-faqs-header">
						<h3><?php esc_html_e( 'What’s your refund policy?', 'mass-pages-posts-creator' ); ?></h3>
					</div>
					<div class="upgrade-faqs-body">
						<p><?php esc_html_e( 'We have a 14-day money-back guarantee.', 'mass-pages-posts-creator' ); ?></p>
					</div>
				</div>
				<div class="upgrade-faqs-list">
					<div class="upgrade-faqs-header">
						<h3><?php esc_html_e( 'I have more questions…', 'mass-pages-posts-creator' ); ?></h3>
					</div>
					<div class="upgrade-faqs-body">
						<p>
						<?php 
							echo sprintf(
							    esc_html__('No problem, we’re happy to help! Please reach out at %s.', 'mass-pages-posts-creator'),
							    '<a href="' . esc_url('mailto:hello@thedotstore.com') . '" target="_blank">' . esc_html('hello@thedotstore.com') . '</a>',
							);

						?>
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="upgrade-to-premium-btn">
			<a href="javascript:void(0);" target="_blank" class="upgrade-now"><?php esc_html_e( 'Get The Premium Version', 'mass-pages-posts-creator' ); ?><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="crown" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="svg-inline--fa fa-crown fa-w-20 fa-3x" width="22" height="20"><path fill="#000" d="M528 448H112c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h416c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm64-320c-26.5 0-48 21.5-48 48 0 7.1 1.6 13.7 4.4 19.8L476 239.2c-15.4 9.2-35.3 4-44.2-11.6L350.3 85C361 76.2 368 63 368 48c0-26.5-21.5-48-48-48s-48 21.5-48 48c0 15 7 28.2 17.7 37l-81.5 142.6c-8.9 15.6-28.9 20.8-44.2 11.6l-72.3-43.4c2.7-6 4.4-12.7 4.4-19.8 0-26.5-21.5-48-48-48S0 149.5 0 176s21.5 48 48 48c2.6 0 5.2-.4 7.7-.8L128 416h384l72.3-192.8c2.5.4 5.1.8 7.7.8 26.5 0 48-21.5 48-48s-21.5-48-48-48z" class=""></path></svg></a>
		</div>
	</div>
</div>
</div>
</div>
</div>
<?php 
