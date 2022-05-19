<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Gcb_Form
 * @subpackage Gcb_Form/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Gcb_Form
 * @subpackage Gcb_Form/public
 * @author     Developer Junayed <admin@easeare.com>
 */
class Gcb_Form_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode( 'gcb_login', [$this, 'gcb_login_view'] );
		add_shortcode( 'gcb_registration', [$this, 'gcb_registration_view'] );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Gcb_Form_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Gcb_Form_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/gcb-form-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Gcb_Form_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Gcb_Form_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/gcb-form-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, "ajax_data", array(
			'ajaxurl' 		=> admin_url('admin-ajax.php'),
			'nonce' 		=> wp_create_nonce('ajax-nonce'),
			'max_upload'	=> wp_max_upload_size()
		));

	}

	function upload_profile_image($file){
		global $gcbRegAlerts;
		$gcbRegAlerts = [];

		$wpdir = wp_upload_dir(  );
		$max_upload_size = wp_max_upload_size();
		$fileSize = $file['size'];
		$imageFileType = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));

		$filename = rand(10,100);

		$folderPath = $wpdir['basedir'];
		$uploadPath = $folderPath."/".$filename.".".$imageFileType;
		$uploadedUrl = $wpdir['baseurl']."/".$filename.".".$imageFileType;

		// Allow certain file formats
		$allowedExt = array("jpg", "jpeg", "png", "PNG", "JPG", "gif");

		if(!in_array($imageFileType, $allowedExt)) {
			$gcbRegAlerts['error'] = "Unsupported file format!";
		}

		if ($fileSize > $max_upload_size) {
			$gcbRegAlerts['error'] = "Maximum upload size $max_upload_size";
		}

		if(empty($gcbRegAlerts)){
			if (move_uploaded_file($file["tmp_name"], $uploadPath)) {
				return $uploadedUrl;
			}
		}
	}

	function gcb_set_post_thumbnail($file, $post_id){
		$filename = basename($file);

		$upload_file = wp_upload_bits( $filename, null, @file_get_contents( $file ) );
		if ( ! $upload_file['error'] ) {
			// if succesfull insert the new file into the media library (create a new attachment post type).
			$wp_filetype = wp_check_filetype($filename, null );
			
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_parent'    => $post_id,
				'post_title'     => preg_replace( '/\.[^.]+$/', '', $filename ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);
			
			$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $post_id );
			
			if ( ! is_wp_error( $attachment_id ) ) {
				// if attachment post was successfully created, insert it as a thumbnail to the post $post_id.
				require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
			
				wp_update_attachment_metadata( $attachment_id,  $attachment_data );
				set_post_thumbnail( $post_id, $attachment_id );
				return $attachment_id;
			}
		}
	}

	function gcb_login_view(){
		ob_start();
		require_once plugin_dir_path( __FILE__ )."partials/login.php";
		return ob_get_clean();
	}

	function gcb_registration_view(){
		ob_start();
		require_once plugin_dir_path( __FILE__ )."partials/registration.php";
		return ob_get_clean();
	}

	function login_registration_actions(){
		global $gcbRegAlerts, $gcbloginAlerts, $wpdb;
		if(isset($_POST['gcb_registration']) && wp_verify_nonce( $_POST['gcb_reg_nonce'], 'gcb_nonce' )){
			try {
				$email = sanitize_email( $_POST['gcb_reg_email'] );
				if(!$email){
					return;
				}

				$username = explode("@", $email)[0];
				$pubgmid = intval( $_POST['gcb_reg_pubgmid'] );
				$clan_tag = sanitize_text_field( $_POST['gcb_reg_clan_tag'] );
				$game_name = sanitize_text_field( $_POST['gcb_reg_name'] );
				$reg_team = intval( $_POST['gcb_reg_team'] );
				$profile_image = $_FILES['gcb_reg_profile_image'];

				if ( isset( $reg_team ) && $reg_team == '-1' ) {
					$gcbRegAlerts = '<strong>ERROR</strong>: You must have to select a team.';
					return;
				}

				// Pubgmid unique
				if(!empty($pubgmid)){
					$exist_pubgmid = $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE meta_key = 'pubgmid' AND meta_value = '$pubgmid'");
					if($exist_pubgmid){
						$gcbRegAlerts = '<strong>ERROR</strong>: Looks like this <u>PUBGM ID</u> is already in existence in our database! So, either you "Somehow" forgot that you are already with us or UH-OH! Someone is doing something "Fishy" using YOUR PUBGM ID! Whichever the reason is! we are here to help you out! Contact in our <a href="https://gcb.com.bd/discord/">Discord server</a> and We assure you, we will see the end of it!';
						return;
					}
				}

				$user_id = wp_create_user( $username, rand(), $email );
				if(is_wp_error( $user_id )){
					$code = $user_id->get_error_code();
					$gcbRegAlerts = $user_id->get_error_messages($code)[0];
					return;
				}

				if ( ! empty( $clan_tag ) ) {
					$clan_tag = trim( $clan_tag );
					update_user_meta( $user_id, 'first_name', $clan_tag );
				}
			
				if ( ! empty( $game_name ) ) {
					$game_name = trim( $game_name );
					update_user_meta( $user_id, 'last_name', $game_name );
				}

				if ( ! empty( $reg_team ) ) {
					$reg_team = trim( $reg_team );
					if ( $reg_team <= 0 ) $reg_team = 0;
					update_user_meta( $user_id, 'sp_team', $reg_team );
				}

				$post['post_type'] = 'sp_player';
				$post['post_title'] = trim( $username );
				$post['post_author'] = $user_id;
				$post['post_status'] = 'draft';
				$playe_id = wp_insert_post( $post );

				if(!is_wp_error( $playe_id )){
					$imageUrl = $this->upload_profile_image($profile_image);
					$imageId = $this->gcb_set_post_thumbnail($imageUrl, $playe_id);
					update_user_meta( $user_id, 'player_profile_photo', $imageId );

					// Custom fields ids
					$metrics = [];
					$metrics['pubgmid'] = $pubgmid;
					update_post_meta( $playe_id, 'sp_metrics', $metrics );
					update_user_meta( $user_id, 'pubgmid', $pubgmid );
					
					if ( ! empty( $reg_team ) ) {
						if ( $reg_team <= 0 ) $reg_team = 0;
						update_post_meta( $playe_id, 'sp_current_team', $reg_team );
					}
					
				}else{
					$code = $playe_id->get_error_code();
					$gcbRegAlerts = $playe_id->get_error_messages($code)[0];
					return;
				}

				wp_new_user_notification( $user_id );
				set_transient( 'user_created_success', 'Your account has been created successfully.', 30 );
				wp_safe_redirect( get_the_permalink( get_page_url_by_shortcode('gcb_registration') ) );
				exit;
				
			} catch (\Throwable $th) {
				print_r($th->getMessage());
			}
		}

		if(isset($_POST['gcb_login']) && wp_verify_nonce( $_POST['gcb_login_nonce'], 'gcb_nonce' )){
			$email = $_POST['gcb_login_email'];
			$username = '';
			if($email){
				$username = explode("@", $email)[0];
			}

			if(!$username){
				$gcbloginAlerts = "Invalid username or email.";
			}

			$password = sanitize_text_field( $_POST['gcb_login_password'] );
			if(!$password){
				$gcbloginAlerts = "Invalid username and password.";
			}
			
			$remember = false;
			if(isset($_POST['gcb_login_remember'])){
				if($_POST['gcb_login_remember'] === 'on'){
					$remember = true;
				}
			}
			
			if(!empty($username) && !empty($password)){
				$creds = array(
					'user_login'    => $username,
					'user_password' => $password,
					'remember'      => $remember
				);
	
				$user = wp_signon( $creds, true );
				if ( is_wp_error( $user ) ) {
					$gcbloginAlerts = $user->get_error_message();
					return;
				}

				wp_safe_redirect( home_url(  ) );
				exit;
			}
		}
	}

}
