<?php
/* Plugin Name: BP Show activity liked avatars
 * Plugin URI: http://paaz.ir
 * Description: This plugin allows you to show user avatars below activity who liked that activity before
 * Version: 1.7.1
 * Author: Mahdi Amani
 * Author URI: http://paaz.ir
 * Tag: Buddypress, BP Show activity liked avatars
 * Text Domain: bp-show-activity-liked-avatars
 */

function my_plugin_init() {
load_plugin_textdomain( 'bp-show-activity-liked-avatars', false, dirname(plugin_basename(__FILE__) ) . '/languages/' );
}
add_action('init', 'my_plugin_init');


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


    $bsala_avatar_size = get_option( "bsala_avatar_size" );
    $bsala_fav_text = get_option( "bsala_fav_text" );
    $bsala_style = get_option( "bsala_style" );
    $bsala_fav_no = get_option( "bsala_fav_no" )+1;
	echo "

	<style type='text/css'>
.fav-ul-list  li:nth-child(n+$bsala_fav_no) {display:none;}
.fav-ul-list  li{
display:inline;
}
	</style>

	";
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
	$avatarurl = bp_core_fetch_avatar( array( 'item_id' => $user['user_id'], 'width' => $bsala_avatar_size, 'height' => $bsala_avatar_size, ‘html’ => false ) );
        $link = bp_core_get_user_domain($user['user_id']);
        




        $u_names[++$i] = '<a class="activity_fav_users" href="'.$link.'"'.">$name</a>";

        $u_avatars[++$i] = '<li><a class="activity_fav_users" href="'.$link.'"'.">$avatarurl</a></li>";

    }
    if(count($u_avatars))
    echo '
<div class="fav_box" style="'.$bsala_style.'">
    '.$bsala_fav_text.'
<ul  class="fav-ul-list" >	
 '.implode($u_avatars).'
</ul>
    </div>';

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

add_action('admin_menu', 'bsala_custom_menu_page');

function bsala_custom_menu_page() {
add_submenu_page( 'options-general.php', 'BSALA', 'BSALA Settings', 'edit_others_posts', 'bsala_set', 'bsala_settings' );

}
function bsala_settings(){
	
	if(isset($_POST['bsala_submit'])){
		update_option( "bsala_avatar_size", $_POST['bsala_avatar_size'] );
		update_option( "bsala_fav_text", $_POST['bsala_fav_text'] );
		update_option( "bsala_fav_no", $_POST['bsala_fav_no'] );
		update_option( "bsala_style", $_POST['bsala_style'] );
	}
		$bsala_avatar_size = get_option( "bsala_avatar_size" );
		$bsala_fav_text = get_option( "bsala_fav_text" );
		$bsala_style = get_option( "bsala_style" );
  		$bsala_fav_no = get_option( "bsala_fav_no" );
?>
<div class="wrap">
<h1> <?php _e('BSALA Settings', 'bp-show-activity-liked-avatars'); ?></h1>
<form action="" method="post">
	<table width="100%" border="0" cellspacing="5" cellpadding="5">
  <tr>
    <th scope="row"><?php _e('Avatar size', 'bp-show-activity-liked-avatars');?></th>
    <td><input type="text" name="bsala_avatar_size" value="<?php echo $bsala_avatar_size ?>" /></td>
  </tr>
  <tr>
    <th scope="row"><?php _e('Fav-box Title', 'bp-show-activity-liked-avatars');?></th>
    <td><input type="text" name="bsala_fav_text" value="<?php echo $bsala_fav_text ?>" /></td>
  </tr>
<tr>
    <th scope="row"><?php _e('Number of Avatars', 'bp-show-activity-liked-avatars');?></th>
    <td><input type="text" name="bsala_fav_no" value="<?php echo $bsala_fav_no ?>" /></td>

</tr>
<tr>
        <th valign="top" scope="row"><?php _e('Custom Style', 'bp-show-activity-liked-avatars');?></th>
    <td><input type="text" name="bsala_style" value="<?php echo $bsala_style ?>" /></td>

</tr>
  <tr>
    <th></th>
    <td><input type="submit" name="bsala_submit" value="<?php _e('Save', 'bp-show-activity-liked-avatars');?>" class="button" /></td>
  </tr>
</table>
</form>
</div>

<?php	
}

?>
