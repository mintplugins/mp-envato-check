<?php
/**
 * Plugin Checker Class for the wp_core Plugin by Move Plugins
 * http://moveplugins.com/plugin-checker-class/
 */
if ( !class_exists( 'MP_CORE_Plugin_Checker' ) ){
	class MP_CORE_Plugin_Checker{
		
		public function __construct($args){
			
			//Get args
			$this->_args = $args;
			$this->pluginpath = $this->_args['plugin_subdirectory'] . $this->_args['plugin_filename'];
			
			//If the user has just clicked "Dismiss", than add that to the options table
			add_action( 'admin_init', array( $this, 'mp_core_close_message') );
			
			//Check for plugin in question
			add_action( 'admin_notices', array( $this, 'mp_core_plugin_check_notice') );
			
			// Create update/install plugin page
			add_action('admin_menu', array( $this, 'mp_core_update_plugin_page') );
					
		}
		
		/**
		 * Show notice that plugin should be installed
		 *
		 */
		public function mp_core_plugin_check_notice() {
			
			//Check to see if the user has ever dismissed this message
			if (get_option( 'mp_core_plugin_checker_' . $this->_args['plugin_slug'] ) != "false"){
							
				//Check if plugin exists but is just not active
				if (file_exists('../wp-content/plugins/' . $this->pluginpath) && !in_array( $this->pluginpath, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ){
					
					echo '<div class="updated fade"><p>';
					
					echo __( $this->_args['plugin_message'] . '</p>');					
					
					//Activate button
					echo '<a href="' . wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $this->pluginpath . '&amp;plugin_status=all&amp;paged=1&amp;s=', 'activate-plugin_' . $this->pluginpath) . '" title="' . esc_attr__('Activate this plugin') . '" class="button">' . __('Activate', 'mp_core') . ' "' . $this->_args['plugin_name'] . '"</a>'; 	
					
					//Dismiss button
					$this->mp_core_dismiss_button();
					
					echo '</p></div>';
				
				//Check if plugin neither exists on this server, nor is it active	 	
				}elseif ( !file_exists('../wp-content/plugins/' . $this->pluginpath) && !in_array( $this->pluginpath, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
					
					echo '<div class="updated fade"><p>';
					
					echo __( $this->_args['plugin_message'] . '</p>');
					
					/** If plugins_api isn't available, load the file that holds the function */
					if ( ! function_exists( 'plugins_api' ) )
						require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
 
 
					//Check if this plugin exists in the WordPress Repo
					$args = array( 'slug' => $this->_args['plugin_slug']);
					$api = plugins_api( 'plugin_information', $args );
					
					//If it doesn't, display link which downloads it from your custom URL
					if (isset($api->errors)){ 
						// "Oops! this plugin doesn't exist in the repo. So lets display a custom download button."; 
						printf( __( '<a class="button" href="%s" style="display:inline-block; margin-right:.7em;"> ' . __('Automatically Install', 'mp_core') . ' "' . $this->_args['plugin_name'] . '"</a>' , 'mp_core' ), admin_url( sprintf( 'plugins.php?page=mp_core_update_plugin_page_' .  $this->_args['plugin_slug'] . '&action=install-plugin&plugin=' . $this->_args['plugin_slug']  . '&_wpnonce=%s', wp_create_nonce( 'install-plugin_' . $this->_args['plugin_download_link']  ) ) ) );	
						
					}else{
						//Otherwise display the WordPress.org Repo Install button
						printf( __( '<a class="button" href="%s" style="display:inline-block; margin-right:.7em;"> ' . __('Automatically Install', 'mp_core') . ' "' . $this->_args['plugin_name'] . '"</a>' , 'mp_core' ), admin_url( sprintf( 'update.php?action=install-plugin&plugin=' . $this->_args['plugin_slug'] . '&_wpnonce=%s', wp_create_nonce( 'install-plugin_' . $this->_args['plugin_slug'] ) ) ) );	
					
					}
										
					//Dismiss button
					$this->mp_core_dismiss_button();
					
					echo '</p></div>';
				}
				
			}
		}
		
		/**
		 * Function to display "Dismiss" message
		 *
		 */
		 public function mp_core_dismiss_button(){
			$this->_args['plugin_required'] = (!isset($this->_args['plugin_required']) ? true : $this->_args['plugin_required']);
			if ($this->_args['plugin_required'] == false){
				echo '<form id="mp_core_plugin_checker_close_notice" method="post" style="display:inline-block; margin-left:.7em;">
							<input type="hidden" name="mp_core_plugin_checker_' . $this->_args['plugin_slug'] . '" value="false"/>
							' . wp_nonce_field('mp_core_plugin_checker_' . $this->_args['plugin_slug'] . '_nonce','mp_core_plugin_checker_' . $this->_args['plugin_slug'] . '_nonce_field') . '
							<input type="submit" id="mp_core_plugin_checker_dismiss" class="button" value="Dismiss" /> 
					   </form>'; 
			}
		 }
		
		/**
		 * Function to fire if the Close button has been clicked
		 *
		 */
		 public function mp_core_close_message(){
			if (isset($_POST['mp_core_plugin_checker_' . $this->_args['plugin_slug']])){
				//verify nonce
				if (wp_verify_nonce($_POST['mp_core_plugin_checker_' . $this->_args['plugin_slug'] . '_nonce_field'],'mp_core_plugin_checker_' . $this->_args['plugin_slug'] . '_nonce') ){
					//update option to not show this message
					update_option( 'mp_core_plugin_checker_' . $this->_args['plugin_slug'], "false" );
				}
			}
		 }
	
		/**
		 * Create mp core update plugin page
		 *
		 */
		public function mp_core_update_plugin_page()
		{
			// This WordPress variable is essential: it stores which admin pages are registered to WordPress
			global $_registered_pages;
		
			// Get the name of the hook for this plugin
			// We use "plugins.php" as the parent as we want our page to appear under "plugins.php?page=mp_core_update_plugin_page"
			$hookname = get_plugin_page_hookname('mp_core_update_plugin_page_' .  $this->_args['plugin_slug'], 'plugins.php');
		
			// Add the callback via the action on $hookname, so the callback function is called when the page "plugins.php?page=mp_core_update_plugin_page" is loaded
			if (!empty($hookname)) {
				add_action($hookname, array( $this, 'mp_core_update_check_callback') );
			}
		
			// Add this page to the registered pages
			$_registered_pages[$hookname] = true;
		}
		
		/**
		 * Callback function for the update plugin page above. This page uses the filesystem api to install a plugin
		 */
		public function mp_core_update_check_callback() {
			
			echo '<div class="wrap">';
			
			screen_icon();
						
			echo '<h2>' . __('Install ', 'mp_core') . $this->_args['plugin_name'] . '</h2>';
			
			//Make sure this user has the cpability to install plugins:
			if (!current_user_can('install_plugins')){ die('<p>' . __('You don\'t have permission to do this. Contact the system administrator for assistance.', 'mp_core') . '</p>'); } 
			
			//Make sure the action is set to install-plugin
			if ($_GET['action'] != 'install-plugin'){ die('<p>' . __('Oops! Something went wrong', 'mp_core') . '</p>'); }
									
			//Get the nonce previously set
			$nonce=$_REQUEST['_wpnonce'];
			
			//Check that nonce to ensure the user wants to do this
			if (! wp_verify_nonce($nonce, 'install-plugin_' . $this->_args['plugin_download_link']) ) die('<p>' . __('Security Check', 'mp_core') . '</p>'); 
			
			//Set the method for the wp filesystem
			$method = ''; // Normally you leave this an empty string and it figures it out by itself, but you can override the filesystem method here
			
			//Get credentials for wp filesystem
			$url = wp_nonce_url('plugins.php?page=mp_core_update_plugin_page_' .  $this->_args['plugin_slug']);
			if (false === ($creds = request_filesystem_credentials($url, $method, false, false) ) ) {
			
				// if we get here, then we don't have credentials yet,
				// but have just produced a form for the user to fill in, 
				// so stop processing for now
				
				return true; // stop the normal page form from displaying
			}
				
			//Now we have some credentials, try to get the wp_filesystem running
			if ( ! WP_Filesystem($creds) ) {
				// our credentials were no good, ask the user for them again
				request_filesystem_credentials($url, $method, true, false);
				return true;
			}
			
			//By this point, the $wp_filesystem global should be working, so let's use it get our plugin
			global $wp_filesystem;
			
			//Get the plugins directory and name the temp plugin file
			$upload_dir = $wp_filesystem->wp_plugins_dir();
			$filename = trailingslashit($upload_dir).'temp.zip';
						
			//Download the plugin file defined in the passed in array
			$saved_file = $wp_filesystem->get_contents( $this->_args['plugin_download_link']);
			
			//If we are unable to find the file, let the user know
			if ( ! $saved_file ) {
				die('<p>' . __('Unable to find file! Please contact the author of this plugin.', 'mp_core') . '</p>');
			}
						
			//Place the temp zipped file in the plugins directory
			$created_file = $wp_filesystem->put_contents( $filename, $saved_file, FS_CHMOD_FILE);
			
			//Unzip the temp zip file
			unzip_file($filename, trailingslashit($upload_dir) . '/' . $this->_args['plugin_slug']);
			
			//Delete the temp zipped file
			$wp_filesystem->rmdir($filename);
			
			//If the file was not created, output a message.
			if ( ! $created_file ) {
				echo '<p>' . __('Error saving file!', 'mp_core') . '</p>';
				echo '<p>' . __('Try downloading it directly:', 'mp_core') . $this->_args['plugin_download_link'] . '</p>';
			}
			
			//Display a successfully installed message
			echo '<p>' . __('Successfully Activated ', 'mp_core') .  $this->_args['plugin_name']  . '</p>';
			
			//Display link to plugins page
			echo '<p><a href="' . network_admin_url('plugins.php') . '">' . __('View all Plugins', 'mp_core') . '</a></p>'; 
			
			echo '</div>';
			
			return true;
			
		}

	}
}

