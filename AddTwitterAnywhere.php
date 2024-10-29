<?php
/*
Plugin Name: Add Twitter @Anywhere
Plugin URI: http://www.thegood.com
Description: Adds the Twitter @Anywhere JavaScript code to your blog, enabling a few of the @Anywhere features.
Version: .1
Author: theGOOD
Author URI: http://www.thegood.com
License: GPL2

    Copyright 2010  theGOOD (hello@thegood.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
add_action( 'wp_head', 'addAnywhere' );

function addAnywhere ( $post_id ) {
	$apikey = get_option( 'anywhere_api_key' );
	$version = '1';
	$output = '<script src="http://platform.twitter.com/anywhere.js?id=' . $apikey . '&v='. $version . '" type="text/javascript"></script>
	<script type="text/javascript">
	  twttr.anywhere(onAnywhereLoad);
	  function onAnywhereLoad(twitter) {
	    // configure the @Anywhere environment
		twitter.linkifyUsers();
		twitter.hovercards();
	  };
	</script>';
	
	print $output;
}

/*
* ADMIN MENU
*/
add_action('admin_menu', 'anywhere_plugin_menu');

function anywhere_plugin_menu() {
	add_options_page('Add Twitter @Anywhere Options', 'Add Twitter @Anywhere', 'administrator', 'twitteranywhere', 'anywhere_options');
}

function anywhere_options() {
    // variables for the field and option names 
    $opt_name = 'anywhere_api_key';
    $hidden_field_name = 'anywhere_submit_hidden';
    $anywhere_apikey_field = 'anywhere_api_key';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val = $_POST[ $anywhere_apikey_field ];

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );

        // Put an options updated message on the screen
?>
	<div class="updated"><p><strong><?php _e('Options saved.', 'anywhere_trans_domain' ); ?></strong></p></div>
<?php
	}
    // Now display the options editing screen
    echo '<div class="wrap">';
    // header
    echo "<h2>" . __( 'Add Twitter @Anywhere Options', 'anywhere_trans_domain' ) . "</h2>";
    // options form
?>
	<form name="form1" method="post" action="">
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

	<p>In order to use @Anywhere, you must first register your blog for a free API key with Twitter. You can do so at the following URL: <a href="http://dev.twitter.com/apps" target="_blank">http://dev.twitter.com/apps</a></p>
	<p><?php _e("Your Twitter @Anywhere API key:", 'anywhere_trans_domain' ); ?> 
	<input type="text" name="<?php echo $anywhere_apikey_field; ?>" value="<?php echo $opt_val; ?>" size="20">
	</p><hr />

	<p class="submit">
	<input type="submit" name="Submit" value="<?php _e('Update Options', 'anywhere_trans_domain' ) ?>" />
	</p>
	
	</form>
	</div>

	<?php
}
?>