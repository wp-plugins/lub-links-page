<?php
/*
Plugin Name: lub-links-page
Plugin URI: http://linkusback.com
Description: This plugin automates the process of installing the links page for the service http://linkusback.com
Version: 0.1
*/
 
/*
To install:
 
1. Upload lub-php-page.zip to the /wp-content/plugins/ directory for your blog.
2. Unzip it into /wp-content/plugins/lub-php-page/lub-php-page.php
3. Activate the plugin through the 'Plugins' menu in WordPress by clicking "LUB Links Page"
4. Go to your Options Panel and open the "LUB Links Page" submenu. /wp-admin/options-general.php?page=lub-php-page.php
5. Configure the options and setup your site ID.

*/

$lubphppage = '0.1';

$pp_default_lubid = 'your LUB site ID';
$pp_default_title = 'Resources';
$pp_default_slug = 'resources';
$pp_default_pp_help = true;
$pp_default_credit = true;

add_option( 'lub_links_page_lubid', $pp_default_lubid );
add_option( 'lub_links_page_title', $pp_default_title );
add_option( 'lub_links_page_slug', $pp_default_slug );
add_option( 'lub_links_page_pp_help', $pp_default_pp_help );
add_option( 'lub_links_page_credit', $pp_default_credit );

function lub_links_page_options_setup() {
    if( function_exists( 'add_options_page' ) ){
        add_options_page( 'LUB Links Page', 'LUB Links Page', 8, 
                          basename(__FILE__), 'lub_links_page_options_page');
    }

}

function lub_links_page_options_page(){
    global $lub_links_page_ver;
    global $pp_default_lubid;
    global $pp_default_title;
    global $pp_default_slug;
    global $pp_default_pp_help;
    global $pp_default_credit;

    if( isset( $_POST[ 'set_defaults' ] ) ){

        echo '<div id="message" class="updated fade"><p><strong>';

	update_option( 'lub_links_page_title', $pp_default_title );
	update_option( 'lub_links_page_lubid', $pp_default_lubid );
	update_option( 'lub_links_page_slug', $pp_default_slug );
	update_option( 'lub_links_page_pp_help', $pp_default_pp_help );
	update_option( 'lub_links_page_credit', $pp_default_credit );

	echo 'Default LUB Links Page options loaded!';
	echo '</strong></p></div>';

    } else if( isset( $_POST[ 'create_page' ] ) ){

        echo '<div id="message" class="updated fade"><p><strong>';

	$title = trim(stripslashes( (string) $_POST[ 'lub_links_page_title' ] ));
	$slug  = trim(stripslashes( (string) $_POST[ 'lub_links_page_slug' ] ));
	$lubid = trim(stripslashes( (string) $_POST['lub_links_page_lubid' ] ));

	update_option( 'lub_links_page_title', $title );
	update_option( 'lub_links_page_lubid', $lubid );
	update_option( 'lub_links_page_slug', $slug );

	$post_title = $title;
	$post_content = '<!-- lub_links_page -->';
	$post_status = 'publish';
	$post_author = 1;
	$post_name = $slug;
	$post_type = 'page';

	$post_data = compact( 'post_title', 'post_content', 'post_status',
		              'post_author', 'post_name', 'post_type' );

	$postID = wp_insert_post( $post_data );

	if( !$postID ){
	    echo 'LUB Links Page page could not be created';
	} else {
	    echo 'LUB Links Page page (ID ' . $postID . ') was created';
	}

	echo '</strong></p></div>';
    } else if( isset( $_POST[ 'info_update' ] ) ){

        echo '<div id="message" class="updated fade"><p><strong>';

	update_option( 'lub_links_page_lubid', trim( stripslashes( (string) $_POST['lub_links_page_lubid' ] )));
	update_option( 'lub_links_page_title', trim( stripslashes( (string) $_POST['lub_links_page_title' ] )));
	update_option( 'lub_links_page_slug', trim( stripslashes( (string) $_POST['lub_links_page_slug' ] )));
	update_option( 'lub_links_page_pp_help', (bool) $_POST['lub_links_page_pp_help'] );
	update_option( 'lub_links_page_credit', (bool) $_POST['lub_links_page_credit'] );

	echo 'Configuration updated!';
	echo '</strong></p></div>';
    }

    ?>

    <div class="wrap">
    <h2>LUB Links Page <?php echo $lub_links_page_ver; ?></h2>
    <p>The <a href="http://linkusback.com" target="_blank">LUB Links Page Plugin for WordPress</a> automatically generates the linkusback.com  
    links page. Please note that you have to be a paying member in the LinkusBack system in order to be able use this plugin, although the plugin itself comes free of charge.    </p>

    <p>To use the plugin, insert the trigger text <strong>&lt;!--&nbsp;lub_links_page&nbsp;--&gt;</strong> into an existing page. The trigger will be
    automatically replaced with a complete link page.</p>

    <p>For your convenience, the plugin can also create a new links page
    for you. Simply fill in the title and slug (path) details and press
    the "Create Page" button to create the LUB links page. The trigger text
    will be added automatically to the new page.</p>
    <p style="color:red"><b>If you let the LUB Links Page Plugin create your site, make sure that you have entered your site ID! Otherwise the page created will not work properly and you might get the warning "bad request" when you try to view the page</b></p>

    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
    <input type="hidden" name="info_update" id="info_update" value="true" />

    <fieldset class="options">
    <legend>Site ID</legend>

    <table width="100%" border="0" cellspacing="0" cellpadding="6">

    <tr valign="top">
      <td align="left" valign="middle">Your LUB Site ID 
         <input name="lub_links_page_lubid" type="text" size="20" value="<?php echo htmlspecialchars( get_option( 'lub_links_page_lubid' ) ); ?>" />
      </td>
    </tr>

    </table>

    </fieldset>

    <div class="submit">
      <input type="submit" name="set_defaults" value="<?php _e('Load Default Options'); ?> &raquo;" />
      <input type="submit" name="info_update" value="<?php _e('Update options' ); ?> &raquo;" />
    </div>

    <fieldset class="options">
    <legend>Page Creation</legend>

    <table width="100%" border="0" cellspacing="0" cellpadding="6">

    <tr valign="top">
      <td align="right" valign="middle"><strong>Page Title</strong></td>
      <td align="left" valign="middle">
         <input name="lub_links_page_title" type="text" size="40" 
                value="<?php echo htmlspecialchars( get_option( 'lub_links_page_title' ) ); ?>" />
      </td>
    </tr>

    <tr valign="top">
      <td align="right" valign="middle"><strong>Page Slug</strong></td>
      <td align="left" valign="middle">
         <input name="lub_links_page_slug" type="text" size="40" 
                value="<?php echo htmlspecialchars( get_option( 'lub_links_page_slug' ) ); ?>" />
      </td>
    </tr>

    </table>

    </fieldset>

    <div class="submit">
      <input type="submit" name="create_page" value="Create Page" />
    </div>

    </form>
    
    </div><?php
}

function lub_links_page_process($content) {

    $tag = "<!-- lub_links_page -->";
	
    // Quickly leave if nothing to replace
    
    if( strpos( $content, $tag ) == false ) return $content;

    // Otherwise generate the LUB links PHPLUB links PHPLUB links PHP and sub it in

    return str_replace( $tag, lub_links_page_html(), $content );
}

function lub_links_page_html(){
    $sitename = get_option( 'lub_links_page_sitename' );
    $link_pp_help = get_option( 'lub_links_page_pp_help' );
    $link_credit = get_option( 'lub_links_page_credit' );
    $lubid = get_option('lub_links_page_lubid');
		
		
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPGET, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
	curl_setopt($ch, CURLOPT_URL, "http://linkusback.com/linkup.php?site_id=$lubid&showpage=".$_GET['pg']);
	$html = curl_exec($ch);
	curl_close($ch);
	echo $html;

    return $pp;
}

add_filter('the_content', 'lub_links_page_process');
add_action('admin_menu', 'lub_links_page_options_setup');

?>
