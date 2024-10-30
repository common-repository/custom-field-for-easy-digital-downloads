<?php
/**
 * This class is loaded on the back-end since its main job is 
 * to display the Admin to box.
 */

class CFEDD_Frontend {
	
	public function __construct () {
		add_filter( 'edd_purchase_link_top', array( $this, 'edd_purchase_link_top_c' ) );
		add_filter( 'edd_add_to_cart_item',  array( $this, 'filter_edd_add_to_cart_item' ), 10, 1 );
		add_action( 'edd_checkout_cart_item_title_after', array( $this, 'action_edd_checkout_cart_item_title_after' ), 10, 2 ); 
		add_action( 'edd_purchase_receipt_after_files', array( $this, 'action_edd_purchase_receipt_after_files' ), 10, 3 ); 
		add_action( 'edd_view_order_details_files_after', array( $this, 'action_edd_view_order_details_files_after' ), 10, 1 );
	}
	function edd_purchase_link_top_c(){

		$args = array(
					    'post_type' => 'cfedd',
						'post_status' => 'publish',
						'posts_per_page' => -1
					);
		$postslist = get_posts( $args );
		if (!empty($postslist)) {
			foreach ($postslist as $postslistk => $postslistv) {
				$post_id = $postslistv->ID;
				$c_type = get_post_meta( $post_id, 'field_type_cfedd', true );
				$c_key = 'field_cfedd'.$post_id;
				if ($c_type=='select') {
					?>
					<label><?php echo $postslistv->post_title;?></label>
					<select name="<?php echo $c_key;?>" >
						<?php
						$field_option_cfedd = get_post_meta( $post_id, 'field_option_cfedd', true );
						$field_option_cfeddar = explode("\n", $field_option_cfedd);
						$field_option_cfeddarr = array();
						foreach ($field_option_cfeddar as $keya => $valuea) {
							echo '<option>'.$valuea.'</option>';
						}
						?>
					</select>
					<?php
				}else{
					?>
					 <label><?php echo $postslistv->post_title;?></label>
					 <input type="text" name="<?php echo $c_key;?>">
					<?php
				}
			}
		}
	}
	function filter_edd_add_to_cart_item( $item ) { 
	    if( isset( $item['id'] ) ) {
	    	$params = array();
			parse_str($_REQUEST['post_data'], $params);
			$args = array(
					    'post_type' => 'cfedd',
						'post_status' => 'publish',
						'posts_per_page' => -1
					);
			$postslist = get_posts( $args );
			if (!empty($postslist)) {
				foreach ($postslist as $postslistk => $postslistv) {
					$post_id = $postslistv->ID;
					$c_type = get_post_meta( $post_id, 'field_type_cfedd', true );
					$c_key = 'field_cfedd'.$post_id;
					if( isset( $params[$c_key] ) ) {
						$item['options'][$c_key] = $params[$c_key];
					}
				}
			}
		   	
	    }
	    return $item; 
	}
	function action_edd_checkout_cart_item_title_after( $item, $key ) { 
  			$args = array(
					    'post_type' => 'cfedd',
						'post_status' => 'publish',
						'posts_per_page' => -1
					);
			$postslist = get_posts( $args );
			if (!empty($postslist)) {
				foreach ($postslist as $postslistk => $postslistv) {
					$post_id = $postslistv->ID;
					$c_type = get_post_meta( $post_id, 'field_type_cfedd', true );
					$c_key = 'field_cfedd'.$post_id;
					?>
					<p><strong><?php echo $postslistv->post_title;?>:</strong><?php echo $item['options'][$c_key];?></p>
					<?php
				}
			}
	    
	     
	}
	function action_edd_purchase_receipt_after_files( $item_id, $payment_id, $meta ) { 
	    // make action magic happen here... 
	   
	    foreach ($meta['downloads'] as $key => $value) {
	    	if($value['id']==$item_id){
	    		$args = array(
					    'post_type' => 'cfedd',
						'post_status' => 'publish',
						'posts_per_page' => -1
					);
				$postslist = get_posts( $args );
				if (!empty($postslist)) {
					foreach ($postslist as $postslistk => $postslistv) {
						$post_id = $postslistv->ID;
						$c_type = get_post_meta( $post_id, 'field_type_cfedd', true );
						$c_key = 'field_cfedd'.$post_id;
						?>
						<p><strong><?php echo $postslistv->post_title;?>:</strong><?php echo $value['options'][$c_key];?></p>
						<?php
					}
				}
	    	}
	    }
	}

	function action_edd_view_order_details_files_after( $payment_id ) { 
		$meta = get_post_meta($payment_id,'_edd_payment_meta',true);
		?>
	    <div id="edd-custom-meta" class="postbox">
	    	<h3 class="hndle">
				<span><?php _e( 'custom meta', 'easy-digital-downloads' ); ?></span>
			</h3>
			<div class="inside edd-clearfix">
				<?php
				foreach ($meta['cart_details'] as $key => $value) {
					$args = array(
						    'post_type' => 'cfedd',
							'post_status' => 'publish',
							'posts_per_page' => -1
						);
					$postslist = get_posts( $args );
					if (!empty($postslist)) {
						foreach ($postslist as $postslistk => $postslistv) {
							$post_id = $postslistv->ID;
							$c_type = get_post_meta( $post_id, 'field_type_cfedd', true );
							$c_key = 'field_cfedd'.$post_id;
							?>
							<div class="column-container">
							<div class="column">
								<?php echo $value['name'];?>
							</div>
							<div class="column">
								<strong><?php echo $postslistv->post_title;?>:</strong><?php echo $value['item_number']['options'][$c_key];?>
							</div>
						</div>
							
							<?php
						}
					}
				?>
				
				<?php
				}
				?>
			</div>
	    </div>
	    <?php
	}
         

}