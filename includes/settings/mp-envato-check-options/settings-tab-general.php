<?php			
/**
 * This is the code that will create a new tab of settings for your page.
 * To create a new tab and set up this page:
 * Step 1. Duplicate this page and include it in the "class initialization function".
 * Step 1. Do a find-and-replace for the term 'mp_envato_check_settings' and replace it with the slug you set when initializing this class
 * Step 2. Do a find and replace for 'general' and replace it with your desired tab slug
 * Step 3. Go to line 17 and set the title for this tab.
 * Step 4. Begin creating your custom options on line 30
 * Go here for full setup instructions: 
 * http://moveplugins.com/settings-class/
 */

/**
* Create new tab
*/
function mp_envato_check_settings_general_new_tab( $active_tab ){
	
	//Create array containing the title and slug for this new tab
	$tab_info = array( 'title' => __('Envato Check Settings Settings' , 'mp_envato_check'), 'slug' => 'general' );
	
	global $mp_envato_check_settings; $mp_envato_check_settings->new_tab( $active_tab, $tab_info );
		
}
//Hook into the new tab hook filter contained in the settings class in the Move Plugins Core
add_action('mp_envato_check_settings_new_tab_hook', 'mp_envato_check_settings_general_new_tab');

/**
* Create the options for this tab
*/
function mp_envato_check_settings_general_create(){
	
	register_setting(
		'mp_envato_check_settings_general',
		'mp_envato_check_settings_general',
		'mp_core_settings_validate'
	);
	
	add_settings_section(
		'envato_check_settings',
		__( 'Envato Check Settings', 'mp_envato_check' ),
		'__return_false',
		'mp_envato_check_settings_general'
	);
	
	add_settings_field(
		'enable_disable',
		__( 'Enable/Disable Envato Check', 'mp_envato_check' ), 
		'mp_core_select',
		'mp_envato_check_settings_general',
		'envato_check_settings',
		array(
			'name'        => 'enable_disable',
			'value'       => mp_core_get_option( 'mp_envato_check_settings_general',  'enable_disable' ),
			'description' => __( 'Do you want the Envato Checker to be enabled or disabled?', 'mp_envato_check' ),
			'registration'=> 'mp_envato_check_settings_general',
			'options'=> array('enabled', 'disabled')
		)
	);
	
	add_settings_field(
		'envato_username',
		__( 'Envato Username', 'mp_envato_check' ), 
		'mp_core_textbox',
		'mp_envato_check_settings_general',
		'envato_check_settings',
		array(
			'name'        => 'envato_username',
			'value'       => mp_core_get_option( 'mp_envato_check_settings_general',  'envato_username' ),
			'description' => __( 'Enter your Envato Username', 'mp_envato_check' ),
			'registration'=> 'mp_envato_check_settings_general',
		)
	);
	
	add_settings_field(
		'envato_api_key',
		__( 'Envato API Key', 'mp_envato_check' ), 
		'mp_core_textbox',
		'mp_envato_check_settings_general',
		'envato_check_settings',
		array(
			'name'        => 'envato_api_key',
			'value'       => mp_core_get_option( 'mp_envato_check_settings_general',  'envato_api_key' ),
			'description' => __( 'Enter your Envato API Key', 'mp_envato_check' ),
			'registration'=> 'mp_envato_check_settings_general',
		)
	);
	
	add_settings_field(
		'redirect_page',
		__( 'Redirect Page', 'mp_envato_check' ), 
		'mp_core_select',
		'mp_envato_check_settings_general',
		'envato_check_settings',
		array(
			'name'        => 'redirect_page',
			'value'       => mp_core_get_option( 'mp_envato_check_settings_general',  'redirect_page' ),
			'description' => __( 'Select the page you want to redirect your users to after they create an account', 'mp_envato_check' ),
			'registration'=> 'mp_envato_check_settings_general',
			'options'=> mp_core_get_all_pages() 
		)
	);
	
	add_settings_field(
		'envato_message',
		__( 'Envato Message', 'mp_envato_check' ), 
		'mp_core_wp_editor',
		'mp_envato_check_settings_general',
		'envato_check_settings',
		array(
			'name'        => 'envato_message',
			'value'       => mp_core_get_option( 'mp_envato_check_settings_general',  'envato_message' ),
			'description' => __( 'This is the message that will appear over the Purchase Code verification form.', 'mp_envato_check' ),
			'registration'=> 'mp_envato_check_settings_general',
		)
	);
	
	//additional general settings
	do_action('mp_envato_check_settings_additional_general_settings_hook');
}
add_action( 'admin_init', 'mp_envato_check_settings_general_create' );