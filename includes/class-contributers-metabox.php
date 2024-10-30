<?php

class cwp_contributers_metabox {

	function __construct() {
		add_action('admin_init',array($this,'cwp_assign_contributer_metabox'));	
		add_action('add_meta_boxes',array($this,'cwp_assign_contributer_metabox'));	
		add_action('save_post',array($this,'cwp_save_contrubuters'));
		add_filter( 'the_content', array($this,'cwp_front_end_contributers'), 20 );
		add_action('wp_enqueue_scripts',array($this,'cwp_enqueue_styles'));
		add_action('admin_enqueue_scripts',array($this,'cwp_admin_enqueue_styles'));
	}

	function cwp_assign_contributer_metabox() {
		add_meta_box( 'cotribute-meta', __( 'Contributors', 'contributer-listing' ), array( $this, 'cwp_assign_contributer_callback' ),'post' );
	}

	function cwp_assign_contributer_callback($post) {
		$contributers = get_users();
		$post_id = get_the_ID();
		$option=get_post_meta($post_id,'contributers');
		foreach ( $contributers as $key => $user ) { 
			?>	
				<div class="c_parent">
					<input type="checkbox" name="contributers[]" value="<?php if($user) echo $user->data->ID;?>" <?php if($option)  if(in_array($user->data->ID,$option[0])  ) { echo "checked"; } ?> > 
					<?php 
						echo '<span class="c_role">' .esc_html( $user->roles[0]).'</span>'; 
				  		echo '<span class="c_name">' . esc_html( $user->display_name ) . '</span>';
					?>
				</div>
			<?php
		}
	}

	function cwp_save_contrubuters($post) {
		$post_id = get_the_ID();
		if ( !current_user_can( 'edit_post', $post_id ))
    		return;

  		if(isset($_POST['contributers'])) {
  			update_post_meta($post_id, 'contributers', $_POST['contributers']);    
  		}

	}

	function cwp_front_end_contributers($content) {
		if ( is_single() ) {
	    	// Add image to the beginning of each page
	    	ob_start();
			$post_id = get_the_ID();
			$contributers=get_post_meta($post_id,'contributers'); 
			?>
				<label><?php esc_html_e('Contributors','contributer-listing'); ?></label>
				<div class="contributer">
					<?php 
						if($contributers) {
							foreach ( $contributers[0] as $key => $user ) { 
								?>
									<div class="contributer-box">
											<img src="<?php echo get_avatar_url($user); ?>" height=70 width=70>
										<div class="contributor-name">
											<?php $author_obj = get_user_by('id', $user); ?>
											<a href="<?php echo get_author_posts_url($user); ?>">
												<?php  esc_html_e($author_obj->data->display_name,'contributer-listing'); ?>		
											</a>
										</div>
									</div>	
								<?php
							}
						} 
					?>
				</div>
			<?php
				$content=ob_get_clean();
			    return $content;
	    }
	}

	function cwp_enqueue_styles() {
		wp_enqueue_style( 'cwp-main-style', plugins_url('../assets/css/style.css',__FILE__));
	}

	function cwp_admin_enqueue_styles() {
		wp_enqueue_style( 'cwp-main-admin-style', plugins_url('../assets/css/admin-style.css',__FILE__));
	}

}

