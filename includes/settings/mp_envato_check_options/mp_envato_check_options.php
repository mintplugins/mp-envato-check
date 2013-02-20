<?php			
/**
 * This is the code that will create a new page of settings for your page.
 * To set up this page:
 * Step 1. Include this page in your plugin/theme
 * Step 2. Do a find-and-replace for the term 'mp_envato_check_settings' and replace it with the slug you desire for this page
 * Step 3. Go to line 17 and set the title, slug, and type for this page.
 * Step 4. Include options tabs.
 * Go here for full setup instructions: 
 * http://moveplugins.com/settings-class/
 */

function mp_envato_check_settings(){
	
	//Set args for new administration menu
	$args = array('title' => __('Envato Check', 'mp_core'), 'slug' => 'mp_envato_check_settings', 'type' => 'options');
	
	//Initialize settings class
	global $mp_envato_check_settings;
	$mp_envato_check_settings = new MP_CORE_Settings($args);
	
	//Include other option tabs
	include_once( 'settings-tab-general.php' );
}
add_action('plugins_loaded', 'mp_envato_check_settings');