<?php
/**
 * Enqueue Syntax Highlighter Scripts and Styles
 * http://alexgorbatchev.com/SyntaxHighlighter/manual/installation.html
 */
 
function mp_envato_check_verify_license(){
	
}
add_action('init', 'mp_envato_check_verify_license');

/**
 * Shortcode which is used to display the Envato API form
 */
function mp_envato_check_display_api_form() {
	global $wpdb;
	$mp_envato_check_msg = '';
	//Run if form has been submitted
	if (isset($_POST['mp_envato_licence'])){
		// Setup Call
		$envato_apikey = mp_core_get_option( 'mp_envato_check_settings_general',  'envato_api_key' );
		$envato_username = mp_core_get_option( 'mp_envato_check_settings_general',  'envato_username' );
		$license_to_check = $_POST['mp_envato_licence'];
		//Initialize curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://marketplace.envato.com/api/edge/'.$envato_username.'/'.$envato_apikey.'/verify-purchase:'.$license_to_check.'.json');
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$ch_data = curl_exec($ch);
		curl_close($ch);
		
		//Email
		$email = $_POST['mp_email'];
		$email_confirm = $_POST['mp_email_confirm'];
		
		//Name
		$firstname = $_POST['mp_firstname'];
		$lastname = $_POST['mp_lastname'];
		
		// Check if key already in database
		$check = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->usermeta WHERE meta_key = 'license_key' AND meta_value = '%s' LIMIT 1", stripslashes($_POST['mp_envato_licence'])));
		
		if( is_email($email) && $email == $email_confirm ){
			
			if ($check > 0){
				// Return an error message in the form
				$mp_envato_check_msg = __('Sorry, this key is already in the database. If you think this is a mistake, please contact us to resolve the issue.', 'mp_envato_check');
			}else{
				// Verify Key		
				$response = json_decode($ch_data, true);
				
				// If Verify Purchase is set
				if( isset($response['verify-purchase']['buyer']) ){
					
					// Setup
					$envato_data = $response['verify-purchase'];
					$buyer = $envato_data['buyer'];
					
					// Check if user exists								
					$user_id = username_exists($buyer);
					
					if ( !$user_id && !email_exists($email)){
						
						// Create new user
						$random_password = wp_generate_password( 12, false );
						$user_id = wp_create_user( $buyer, $random_password, $email );
						wp_new_user_notification( $user_id, $random_password );
						wp_update_user( array ('ID' => $user_id, 'first_name' => $firstname, 'last_name' => $lastname) ) ;
						
						//Add the key to the new user
						update_user_meta( $user_id, 'license_key', stripslashes($_POST['mp_envato_licence']) );
						
						//log the new user in
						wp_set_current_user( $user_id, $buyer );
						
						//Redirect user to page selected in plugin settings
						wp_redirect( get_permalink( intval(mp_core_get_option( 'mp_envato_check_settings_general', 'redirect_page' ) ) ) ); exit;
					}else{
						$mp_envato_check_msg = __('The Email or Envato username on this purchase code / email address has already been used to create an account.', 'mp_envato_check');
					}
				}//if( isset($response['verify-purchase']['buyer']) ){
				else{
					$mp_envato_check_msg = __('The license key you have entered is not valid or could not be verified at this time.', 'mp_envato_check');
				}
			}//if ($check > 0){
		}//if( is_email($email) && $email == $email_confirm ){
		else{
			$mp_envato_check_msg = __('Check to make sure the email address you entered is valid.', 'mp_envato_check');
		}
		$mp_envato_check_msg .= mp_show_api_form();
	}//if (isset($_POST['mp_envato_licence'])){
	
	//Show the API form
	else{ 
		$mp_envato_check_msg .= mp_show_api_form();
	}
	
	return $mp_envato_check_msg;
}
add_shortcode( 'display_envato_api_form', 'mp_envato_check_display_api_form' );

function mp_show_api_form(){
	 $form_html =  '<p>' . mp_core_get_option( 'mp_envato_check_settings_general',  'envato_message' ) . '</p>';
	
	 $form_html .= '<form action="" method="post" style="width:50%;">
		<p>
			<label for="envato_api">Your Envato Licence</label>
			<input type="text" name="mp_envato_licence" placeholder="Your Envato Licence">
		</p>
		
		<p>
			<label for="mp_email">Your Email Address</label>
			<input type="text" name="mp_email" placeholder="Your Email Address">
		</p>
		
		<p>
			<label for="mp_email_confirm">Confirm Your Email Address</label>
			<input type="text" name="mp_email_confirm" placeholder="Confirm Your Email Address">
		</p>
		
		<p>
			<label for="mp_firstname">Your First Name</label>
			<input type="text" name="mp_firstname" placeholder="Your First Name">
		</p>
		
		<p>
			<label for="mp_lastname">Your Last Name</label>
			<input type="text" name="mp_lastname" placeholder="Your Last Name">
		</p>
	
		<p class="submit">
			<input type="submit" class="button green" value="Register">
		</p>
	</form>';
	
	return $form_html;	
}
