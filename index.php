<?php
/**
 * @package Survey Maker Lite
 * @version 0.8
 */
/*
Plugin Name: Survey Maker Lite
Plugin URI: http://misterpah.com
Description: Build Survey in your wordpress
Author: Misterpah
Version: 0.8
Author URI: http://misterpah.com
*/


$url = plugin_dir_path(__FILE__);
global $wpdb;
define("PLUGIN_NAME","survey-maker-lite");
define("PLUGIN_SLUG","sm");
define("PLUGIN_PATH",plugin_dir_path(__FILE__));
define("PLUGIN_URL",plugins_url() . "/".PLUGIN_NAME."/");
define("PLUGIN_TABLE_INDEX",$wpdb->prefix . PLUGIN_SLUG);
define("PLUGIN_TABLE_SURVEY",$wpdb->prefix . PLUGIN_SLUG."_survey");
define("PLUGIN_TABLE_RESULT",$wpdb->prefix . PLUGIN_SLUG."_result");


include PLUGIN_PATH . "php/shortcode.php";

add_action('admin_menu', 'mqc_admin_actions');

register_activation_hook(__FILE__,'sm_install'); // installation






function sm_install()
	{
	$sql = "CREATE TABLE IF NOT EXISTS ".PLUGIN_TABLE_INDEX." (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
			  surveyName text NOT NULL,
			  UNIQUE KEY id (id)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;";   
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);  
	
	$sql = "CREATE TABLE IF NOT EXISTS ".PLUGIN_TABLE_SURVEY." (
			id int(11) NOT NULL AUTO_INCREMENT,
			surveyId int(11) NOT NULL,
			qa longtext NOT NULL,
			PRIMARY KEY (id)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
	dbDelta($sql);

	$sql = "CREATE TABLE IF NOT EXISTS ".PLUGIN_TABLE_RESULT." (
			id int(11) NOT NULL AUTO_INCREMENT,
			surveyId int(11) NOT NULL,
			result longtext NOT NULL,
			timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
	dbDelta($sql);

	$welcome_name = "Mr. WordPress";
	$welcome_text = "Congratulations, you just completed the installation!";

	$rows_affected = $wpdb->insert( $table_name, array( 'time' => current_time('mysql'), 'name' => $welcome_name, 'text' => $welcome_text ) );		
	}



function mqc_admin_actions() 
	{
	# create menu item in the admin panel
	$mfb_page_title = "Survey Maker Lite"; //The text to be displayed in the title tags of the page when the menu is selected
	$mfb_menu_title = "Survey Maker Lite"; //The on-screen name text for the menu
	$mfb_capability = "manage_options"; //The capability required for this menu to be displayed to the user. User levels are deprecated and should not be used here!
	$mfb_menu_slug = "sm"; // The slug name to refer to this menu by (should be unique for this menu).
	$mfb_page_function = "drawAdminPage"; //The function that displays the page content for the menu page.
	add_menu_page( $mfb_page_title, $mfb_menu_title, $mfb_capability, $mfb_menu_slug, $mfb_page_function);
	}

function drawAdminPage(){
	include PLUGIN_PATH."php/tableClass.php";
	$url = plugins_url() . "/misterpah-questionaire/";
	?>
	<div class='wrap'>
		<?php

		$action = @$_GET['action'];
		if ($action == null)  //if-not-edit
			{
			include PLUGIN_PATH."php/admin_index.php";
			} //end if-not-edit


		if ($action == "edit") // if-edit
			{	
				include $plugin_path . "php/admin_edit.php";
			} // end if-edit
			
		if ($action == "result") // if-result
			{	
				include $plugin_path . "php/admin_result.php";
			} // end if-result	
	
		if ($action == "new") // if-new
			{	
				include $plugin_path . "php/admin_new.php";
			} // end if-new
	
		if ($action == "delete") // if-delete
			{	
				include $plugin_path . "php/admin_delete.php";
			} // end if-delete
			/*
		if ($action == "import") // if-import
			{	
				include $plugin_path . "php/admin_import.php";
			} // end if-import
			
		if ($action == "export") // if-export
			{	
				include $plugin_path . "php/admin_export.php";
			} // end if-export
			*/
	echo "</div>"; // end div:wrap
}  // end drawAdminPage



function sanitize_php ($string)
	{
	$string = str_replace(">",'',$string); // unacceptable !
	$string = str_replace("<",'',$string); // unacceptable !
	$string = str_replace("]",'',$string); // unacceptable !
	$string = str_replace("[",'',$string); // unacceptable !
	$string = str_replace("\"",'-doublequote-',$string);
	$string = str_replace("'","-singlequote-",$string);
	$string = str_replace("\\","-backslash-",$string);
	$string = str_replace("/","-slash-",$string);
	return $string;
	}
		



add_action('init', 'sm_intercept_post');
function sm_intercept_post()
	{

	if ($_POST['sm'] != null)
		{

		$post = $_POST['sm'];
		$surveyId = $post['surveyId'];

		
		unset($post['surveyId']);
		
		foreach ($post as &$cur) // combine array into 1 string
			{
			if (!is_array($cur))
				{
				$cur = sanitize_php($cur);
				}
			if (is_array($cur))
				{
				array_shift($cur);
				for ($i = 0 ;$i < count($cur);$i++)
					{
					$cur = str_replace(",",'-&-',$cur);
					}
				$cur = implode(",",$cur);
				$cur = sanitize_php($cur);
				}
			}
		
		
		$post = serialize($post);
		

		// now connect to database and store data
		global $wpdb;
		
		$SQL = "INSERT INTO ".PLUGIN_TABLE_RESULT." (id, surveyId, result, timestamp) VALUES (NULL, '$surveyId', '$post', CURRENT_TIMESTAMP);";		
		$wpdb->query($SQL);		
		
		}
	}



?>
