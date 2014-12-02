<?php

function hrm_jobs_render_admin() {
	$args = array(
		'post_type' => 'job',
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'post_per_page' => 100, /* add a reasonable max # rows */
		'no_found_rows' => true, /* don't generate a count as part of query */

	);

	$jobs = new WP_Query( $args );
	?>
		<div id="jobs-admin-sort" class="wrap">
		<div id="icon-job-admin" class="icon32"><br /></div>
		<h2><?php _e('Sort Job Positions', 'hrm_jobs'); ?> <img src="<?php echo esc_url( admin_url() . '/images/loading.gif' ); ?>" id="loading-animation" /></h2>
			<?php if ( $jobs->have_posts() ) : ?>
			<p><?php _e('<strong>Note:</strong> this only affects the Jobs listed using the shortcode functions', 'hrm_jobs'); ?></p>
			<ul id="custom-type-list">
				<?php while ( $jobs->have_posts() ) : $jobs->the_post(); ?>
					<li id="<?php the_id(); ?>"><?php the_title(); ?></li>
				<?php endwhile; ?>
			</ul>
			<?php else: ?>
			<p><?php _e( 'You have no Jobs to sort.', 'hrm_jobs' ); ?></p>
			<?php endif; ?>
		</div>

	<?php
}

add_action( 'admin_menu', 'hrm_jobs_add_menu_page');

function hrm_jobs_save_order() {

	//@todo add a nonce here to verify user intent
	//@todo add a capability check to ensure use caps
	if ( current_user_can( 'manage_options') ) {
		global $wpdb; // WordPress database class
			$order = explode(',', $_POST['order']);
			$counter = 0;
			foreach ($order as $item_id) {
				$wpdb->update( $wpdb->posts, array( 'menu_order' => $counter ), array( 'ID' => (int) $item_id) );
				$counter++;
			}
			die(1);
	} else {
		echo 'you cannot do this'; exit;
	}
}

add_action( 'wp_ajax_save_sort', 'hrm_jobs_save_order' );