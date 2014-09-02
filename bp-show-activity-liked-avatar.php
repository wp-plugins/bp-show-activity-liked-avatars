<?php
/* Plugin Name: BP Show activity liked avatars
 * Plugin URI: http://paaz.ir
 * Description: This plugin allows you to show user avatars below activity who liked that activity before
 * Version: 1.0
 * Author: Mahdi Amani
 * Author URI: http://paaz.ir
 * Tag: Buddypress, BP Show activity liked avatars
 */


register_activation_hook( __FILE__,'saln_activate');

register_deactivation_hook( __FILE__,'saln_deactivate');


function saln_activate()
{
    
}


function saln_deactivate()
{
    
}
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if(is_plugin_active('buddypress/bp-loader.php'))
{
function saln_get_users_fav($activity_id='')
{
    $activity_id = bp_get_activity_id();
    global $wpdb;
    $query = "SELECT user_id FROM ".$wpdb->base_prefix."usermeta WHERE meta_key = 'bp_favorite_activities' AND meta_value LIKE '%:\"$activity_id\";%' ";
    $users = $wpdb->get_results($query,ARRAY_A);
    foreach ($users as $user)
    {
        $name = bp_get_profile_field_data(
	array(
		'field' => 1,
		'user_id' => $user['user_id']
	));
	$avatarurl = bp_core_fetch_avatar( array( 'item_id' => $user['user_id'], 'width' => 30, 'height' => 30, ‘html’ => false ) );
        $link = bp_core_get_user_domain($user['user_id']);
        
        $u_names[++$i] = '<a class="activity_fav_users" href="'.$link.'"'.">$name</a>";
        $u_avatars[++$i] = '<a class="activity_fav_users" href="'.$link.'"'.">$avatarurl</a>";
    }
    if(count($u_names))
    echo '<div class="fav_box">'.implode($u_avatars).'</div>';

    else
    return '';
  
}
}
else
{
   
    function saln_error_notice()
    {
        echo '<div class="error">
       <p>You must need to install and active <strong><a href="'.site_url().'/wp-admin/plugin-install.php?tab=search&type=term&s=buddypress&plugin-search-input=Search+Plugins">
        Buddypress</strong></a> to use <strong>Buddypress Show activity liked names </strong> plugin </p>
    </div>';
    }
    add_action('admin_notices', 'saln_error_notice');

}
add_filter( 'bp_activity_entry_meta', 'saln_get_users_fav',99 );


?>
