<?php
/**
* This class is loaded on the back-end since its main job is
* to display the Admin to box.
*/
class CFEDD_Admin {
	
	public function __construct () {
		add_action( 'init', array( $this, 'CFEDD_init' ) );
		add_action( 'admin_menu', array( $this, 'CFEDD_admin_menu' ) );
		add_action('admin_enqueue_scripts', array( $this, 'CFEDD_admin_script' ));
		if ( is_admin() ) {
			return;
		}
		
	}
	public function CFEDD_admin_script () {
		wp_enqueue_style('cfedd_admin_css', CFEDD_PLUGINURL.'css/admin-style.css');
		wp_enqueue_script('cfedd_admin_js', CFEDD_PLUGINURL.'js/admin-script.js');
	}
	public function CFEDD_init () {
		$args = array(
				'label'               => __( 'cfedd', 'cfedd' ),
				'show_ui'             => false,
				'show_in_menu'        => false,
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => false,
				'menu_position'       => 5,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				);
	
		// Registering your Custom Post Type
		register_post_type( 'cfedd', $args );
		if( current_user_can('administrator') ) {
			if($_REQUEST['action'] == 'add_new_field_cfedd'){
				if(!isset( $_REQUEST['cfedd_nonce_field_add'] ) || !wp_verify_nonce( $_POST['cfedd_nonce_field_add'], 'cfedd_nonce_action_add' ) ){
	                print 'Sorry, your nonce did not verify.';
	                exit;
	            }else{
					$post_data = array(
										'post_title' => sanitize_text_field($_REQUEST['field_name_cfedd']),
										'post_type' => 'cfedd',
										'post_status' => 'publish'
										);
						$post_id = wp_insert_post( $post_data );
						update_post_meta( $post_id, 'field_type_cfedd', sanitize_text_field($_REQUEST['field_type_cfedd']) );
						$textToStore = htmlentities($_REQUEST['field_option_cfedd'], ENT_QUOTES, 'UTF-8');
						update_post_meta( $post_id, 'field_option_cfedd', $textToStore );
						wp_redirect( admin_url( 'admin.php?page=cfedd-fields&msg=success') );
					exit;
				}
			}
			if($_REQUEST['action'] == 'update_new_field_cfedd'){
				if(!isset( $_REQUEST['cfedd_nonce_field_edit'] ) || !wp_verify_nonce( $_POST['cfedd_nonce_field_edit'], 'cfedd_nonce_action_edit' ) ){
	                print 'Sorry, your nonce did not verify.';
	                exit;
	            }else{
					$post_id = sanitize_text_field($_REQUEST['id']);
					$post_data = array(
										'ID'           => $post_id,
										'post_title' => sanitize_text_field($_REQUEST['field_name_cfedd']),
										);
					wp_update_post( $post_data );
					update_post_meta( $post_id, 'field_type_cfedd', sanitize_text_field($_REQUEST['field_type_cfedd']) );
					$textToStore = htmlentities($_REQUEST['field_option_cfedd'], ENT_QUOTES, 'UTF-8');
					update_post_meta( $post_id, 'field_option_cfedd', $textToStore );
					wp_redirect( admin_url( 'admin.php?page=cfedd-fields&msg=success') );
				}
				exit;
			}
			if($_REQUEST['action'] == 'delete_field_cfedd'){
				$post_id = sanitize_text_field($_REQUEST['id']);
				$post_data = array(
									'ID'          => $post_id,
									'post_status' => 'trash'
									);
				wp_update_post( $post_data );
				wp_redirect( admin_url( 'admin.php?page=cfedd-fields&msg=success') );
				exit;
			}
		}
	}
	public function CFEDD_admin_menu () {
		add_menu_page('Custom Field For EDD', 'Custom Field For EDD', 'manage_options', 'cfedd-fields', array( $this, 'CFEDD_page' ));
		/*add_submenu_page( 'theme-options', 'Settings page title', 'Settings menu label', 'manage_options', 'theme-op-settings', 'wps_theme_func_settings');*/
		/*add_submenu_page( 'theme-options', 'FAQ page title', 'FAQ menu label', 'manage_options', 'theme-op-faq', 'wps_theme_func_faq');
		add_options_page('WP Job Google Location', 'WP Job Google Location', 'manage_options', 'CFEDD', array( $this, 'CFEDD_page' ));*/
	}
	public function CFEDD_page() {
?>
<div class="wrap">
	<div class="headingmc">
		<h1 class="wp-heading-inline"><?php _e('EDD Custom Field', 'cfedd'); ?></h1>
		<a href="#" class="page-title-action addnewfielcfqjm"><?php _e('Add New Field', 'cfedd'); ?></a>
	</div>
	<hr class="wp-header-end">
	<?php if($_REQUEST['msg'] == 'success'){ ?>
        <div class="notice notice-success is-dismissible"> 
            <p><strong><?php _e('EDD Custom Field Table Updated', 'cfedd'); ?></strong></p>
        </div>
    <?php } ?>
	<?php
	if($_REQUEST['action']=='edit-cfedd-fields'){
		$id = sanitize_text_field($_REQUEST['id']);
		$postdata = get_post( $id );
		$field_type_cfedd = get_post_meta( $id, 'field_type_cfedd', true );
		$field_option_cfedd = get_post_meta( $id, 'field_option_cfedd', true );
		
		
		?>
		<div class="postbox">
				
				<div class="inside">
					<form action="#" method="post" id="edd_custom_form">
						<?php wp_nonce_field( 'cfedd_nonce_action_edit', 'cfedd_nonce_field_edit' ); ?>
						<h3><?php _e('WP Job Manager Custom Field Edit', 'cfedd'); ?></h3>
						<table class="form-table">
							<tr>
								<th scope="row"><label>Field Type</label></th>
								<td>
									<select name="field_type_cfedd" class="field_type_cfedd" >
										<option value="text" <?php echo (($field_type_cfedd=='text')?'selected':'')?>>Text</option>
										<option value="select" <?php echo (($field_type_cfedd=='select')?'selected':'')?>>Select</option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>Field Name</label></th>
								<td>
									<input type="text" required value="<?php echo $postdata->post_title;?>" class="regular-text" name="field_name_cfedd">
								</td>
							</tr>
							<tr class="cfedd_option" style="<?php echo (($field_type_cfedd=='select')?'':'display: none;');?>">
								<th scope="row"><label>Field Option</label></th>
								<td>
									<textarea  class="regular-text textheighs" name="field_option_cfedd" placeholder="Option 1&#10;Option 2"><?php echo $field_option_cfedd;?></textarea>
									<p class="description">Per Line add one Option</p>
								</td>
							</tr>
						</table>
						
						<p class="submit">
							<input type="hidden" name="action" value="update_new_field_cfedd">
							<input type="hidden" name="edit_id" value="<?php echo $id;?>" >
							<input type="submit" name="submit"  class="button button-primary" value="Save">
						</p>
					</form>
				</div>
			</div>
		<?php
	}else{
	?>
	<table class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
				<th>Field Name</th>
				<th>Field Type</th>
				<th>Key Meta</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$args = array(
					'post_type' => 'cfedd',
					'post_status' => 'publish',
					'posts_per_page' => -1
					);
			$the_query = new WP_Query( $args );
			while ( $the_query->have_posts() ) : $the_query->the_post();
				$post_id = get_the_ID();
			?>
			<tr>
				<td><?php the_title(); ?></td>
				<td><?php echo get_post_meta( get_the_ID(), 'field_type_cfedd', true ); ?></td>
				<td>field_cfedd<?php echo $post_id; ?></td>
				<td>
					<a class="button button-icon tips icon-edit" href="<?php echo admin_url( 'admin.php?page=cfedd-fields&action=edit-cfedd-fields&id='.get_the_ID());?>" ><?php _e('Edit', 'cfedd'); ?></a>
					<a class="button button-icon tips icon-delete" href="<?php echo admin_url( 'admin.php?action=delete_field_cfedd&id='.get_the_ID());?>" ><?php _e('Delete', 'cfedd'); ?></a>
				</td>
			</tr>
			<?php
			endwhile;
			wp_reset_postdata();
			?>
		</tbody>
	</table>
	
	</div>
	<?php
	}
	?>
</div>

<div class="showpopmain">
		<div class="popupinner">
			<div class="postbox">
				<a class="closeicond" href="#"><span class="dashicons dashicons-no"></span></a>
				<div class="inside">
					<form action="#" method="post" id="edd_custom_form">
						<?php wp_nonce_field( 'cfedd_nonce_action_add', 'cfedd_nonce_field_add' ); ?>
						<h3><?php _e('EDD Custom Field Add', 'cfedd'); ?></h3>
						<table class="form-table">
							<tr>
								<th scope="row"><label>Field Type</label></th>
								<td>
									<select name="field_type_cfedd" class="field_type_cfedd">
										<option value="text">Text</option>
										<option value="select">Select</option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>Field Name</label></th>
								<td>
									<input type="text" required class="regular-text" name="field_name_cfedd">
								</td>
							</tr>
							<tr class="cfedd_option" style="display: none;">
								<th scope="row"><label>Field Option</label></th>
								<td>
									<textarea  class="regular-text textheighs" name="field_option_cfedd" placeholder="Option 1&#10;Option 2"></textarea>
									<p class="description">Per Line add one Option</p>
								</td>
							</tr>
						</table>
						
						<p class="submit">
							<input type="hidden" name="action" value="add_new_field_cfedd">
							<input type="submit" name="submit"  class="button button-primary" value="Save">
						</p>
					</form>
				</div>
			</div>
			
		</div>
<?php
}



}
?>