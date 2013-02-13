<?php			
/**
 * This is the code that will create a new tab of settings for your page.
 * To set it up, first do a find-and-replace for the term 'mp_envato_check'
 * Then do a find and replace for 'general'
 * Go here for full setup instructions: 
 * http://moveplugins.com/settings-class/
 */
 
/**
* Display tab at top of Theme Options page
*/
function mp_envato_check_settings_general_tab_title($active_tab){ 
	if ($active_tab == 'mp_envato_check_settings_general'){ $active_class = 'nav-tab-active'; }else{$active_class = "";}
	echo ('<a href="?page=mp_envato_check_settings&tab=mp_envato_check_settings_general" class="nav-tab ' . $active_class . '">Envato Check Settings</a>');
}
add_action( 'mp_envato_check_settings_new_tab_hook', 'mp_envato_check_settings_general_tab_title' );

/**
 * Display the content for this tab
 */
function mp_envato_check_settings_general_tab_content(){
	function mp_envato_check_settings_general() {  
		settings_fields( 'mp_envato_check_settings_general' );
		do_settings_sections( 'mp_envato_check_settings_general' );
	}
}
add_action( 'mp_envato_check_settings_do_settings_hook', 'mp_envato_check_settings_general_tab_content' );

function mp_envato_check_settings_general_create(){
	
	//This variable must be the name of the variable that stores the class.
	global $mp_envato_check_settings_class;
	
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
		array( &$mp_envato_check_settings_class, 'select' ),
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
		array( &$mp_envato_check_settings_class, 'textbox' ),
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
		array( &$mp_envato_check_settings_class, 'textbox' ),
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
		array( &$mp_envato_check_settings_class, 'select' ),
		'mp_envato_check_settings_general',
		'envato_check_settings',
		array(
			'name'        => 'redirect_page',
			'value'       => mp_core_get_option( 'mp_envato_check_settings_general',  'redirect_page' ),
			'description' => __( 'Select the page you want to redirect your users to after they create an account', 'mp_envato_check' ),
			'registration'=> 'mp_envato_check_settings_general',
			'options'=> $mp_envato_check_settings_class->get_all_pages() 
		)
	);
	
	add_settings_field(
		'envato_message',
		__( 'Envato Message', 'mp_envato_check' ), 
		array( &$mp_envato_check_settings_class, 'wp_editor' ),
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