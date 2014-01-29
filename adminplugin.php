<?php
/*
Plugin Name: Central College WordPress Login Notification
Description: Gives administrators the option to post a notification on the WordPress login screen. It also includes Central College branding.
Author: Jacob Oyen '04 - Central College
Version: 0.1 beta
Author URI: http://www.central.edu
Plugin URI: https://github.com/CentralCollege/wordpress-login-notify
*/

//Setup the admin page
add_action('admin_menu', 'cui_login_message');

//Setup the plugin page
function cui_login_message(){
	add_plugins_page('Login Message ', 'Login Message', 'manage_options', 'cui-login-status', 'cui_login_options');
}

//Setup plugin options
function cui_login_options(){
	if (!current_user_can('manage_options')){
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );        
	}
	
	//Save the message for later use if it is posted.
	if(isset($_POST['cui_login_message'])){
		update_option('cui_login_message', $_POST['cui_login_message']);
	}
	//Save the status for later use of it is posted.
	if(isset($_POST['cui_login_status'])){
		update_option('cui_login_status', $_POST['cui_login_status']);
	}

	?>
	<div id="wrap">
		<h2>Login Message</h2>
		<p>Set a message that will display to all users at the login screen.</p>
		<div id="message" class="updated below-h2">
			<h3>Current setting: <?php echo get_option('cui_login_status');?></h3>
			<p><strong>Current message:</strong> <?php echo get_option('cui_login_message');?></p>
		</div>
	</div>
	<form name="form1" method="post" action="">
		<input type="hidden" name="test" value="Y">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="cui_login_status">Login status:</label>
					</th>
					<td>
						<select name="cui_login_status" class="regular-text">
							<option value="normal">Normal</option>
							<option value="maintain">Maintenance Notification</option>
							<option value="unavailable">Unavailable</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="cui_login_message">Login screen message:</label>
					</th>
					<td><input type="text" name="cui_login_message" class="regular-text"></td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
		</p>
	</form>

	<?php
}

//Determine what to display on the login screen
$status = get_option('cui_login_status');

if($status == 'maintain'){
	function cui_customMessage(){
		echo '
		<div class="message">
			<h2 style="text-align:center; margin-bottom: 20px;">Upcoming Maintenance!</h2>
			<p>' . get_option('cui_login_message') . '</p>
		</div>';
	}
	add_action('login_message', 'cui_customMessage');
}
if($status == 'unavailable'){
	//Disable the login screen
	function cui_disableLogin(){
		echo '<link rel="stylesheet" type="text/css" href="' .plugins_url('login-maintain.css', __FILE__). '">';
	}
	add_action('login_head','cui_disableLogin');
	
	//Show message
	function cui_customMessage(){
		echo '
		<div class="message">
			<h2 style="text-align:center; margin-bottom: 20px;">Login disabled!</h2>
			<p>' . get_option('cui_login_message') . '</p>
		</div>';
	}
	add_action('login_message', 'cui_customMessage');
	
}

//Use this for custom Central College login screen
function central_admin_head() {
        echo '<link rel="stylesheet" type="text/css" href="' .plugins_url('login.css', __FILE__). '">';
}
add_action('login_head', 'central_admin_head');


?>