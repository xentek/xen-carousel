<?php
/*
	Plugin Name: XEN Carousel
	Plugin URI: http://xentek.net/code/wordpress/plugins/xen-carousel/
	Description: Easily create a carousel of images for display on your home page or anywhere on your site.
	Version: 0.9.4
	Author: Eric Marden
	Author URI: http://www.xentek.net/
*/
/*  Copyright 2008  Eric Marden  (email : wp@xentek.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$path = WP_PLUGIN_DIR.'/'.dirname(plugin_basename(__FILE__));
$ajaxpath = get_bloginfo( 'wpurl' ).'/wp-admin/admin-ajax.php';
$urlpath = WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__));

add_action('admin_head','xencarousel_admin_head');
add_action('admin_footer','xencarousel_admin_footer');
add_action('admin_enqueue_scripts','xencarousel_admin_scripts');
add_action('admin_menu', 'xencarousel_meta_box');
add_action('wp_ajax_carousel_ajax_search','xencarousel_ajax_search');
add_action('wp_ajax_carousel_ajax_image','xencarousel_ajax_image');
add_action('save_post', '_save_image_data'); 
add_action('wp_enqueue_scripts','xencarousel_scripts');

function xencarousel_admin_head()
{
	global $path, $ajaxpath, $urlpath;

	echo '<link rel="stylesheet" href="'.$urlpath.'/jquery.autocomplete.css" type="text/css" media="screen" charset="utf-8" />'."\n";
}

function xencarousel_admin_footer()
{
    global $path, $ajaxpath, $urlpath;
	echo '<script src="'.$urlpath.'/xencarousel-admin.js.php?ajaxpath='.urlencode($ajaxpath).'&path='.urlencode($urlpath).'&ver=0.9.4" type="text/javascript"></script>'."\n";
}

function xencarousel_admin_scripts()
{
	global $path, $ajaxpath, $urlpath;
	if ( is_admin() )
	{
		$scripts = array( 
			array('name' => 'autocomplete', 'path' => $urlpath.'/jquery.autocomplete.js', 'deps' => array('jquery'), 'ver'=>'1.0.2'),
		 );

		foreach($scripts as $script)
		{
			wp_enqueue_script($script['name'], $script['path'], $script['deps'], $script['ver'], true);
		}
	}
}
	
function xencarousel_scripts()
{
	global $urlpath;
		$scripts = array( 
			array('name' => 'jquery-jcarousel-lite', 'path' => $urlpath.'/jcarousellite.js', 'deps' => array('jquery'), 'ver' => '1.0.1'),
			array('name' => 'jquery-easing', 'path' => $urlpath.'/jquery.easing.js', 'deps' => array('jquery'), 'ver' => '1.1'),
			array('name' => 'jquery-mousewheel', 'path' => $urlpath.'/jquery.mousewheel.js', 'deps' => array('jquery'), 'ver' => '1.1'),
			array('name' => 'xencarousel', 'path' => $urlpath.'/xencarousel.js', 'deps' => array('jquery','jquery-easing','jquery-mousewheel','jquery-jcarousel-lite'), 'ver' => '0.9.1'),
		 );

		foreach($scripts as $script)
		{
			wp_enqueue_script($script['name'], $script['path'], $script['deps'], $script['ver'], true);
		}
}

function xencarousel_meta_box()
{
	if ( function_exists('add_meta_box') )
	{
		add_meta_box('xencarousel',__('xen carousel','xencarousel'),'xencarousel_post_box','post','normal');
		add_meta_box('xencarousel',__('xen carousel','xencarousel'),'xencarousel_post_box','page','normal');
	}
}

function xencarousel_post_box()
{
	if ( isset($_GET['post']) ) {
		$post = $_GET['post'];
		
		if ($attachment_id = get_post_meta($post, '_xencarousel_image_id', true)) {
            $image = _xen_get_attachment_image($attachment_id);
	    }
	    
	} else {
		$post = '-'.time();
		$image = array("",'100','100');
	}
	
	$src = $image[0];
    $width = $image[1];
    $height = $image[2];

?>
<style>
#xencarousel_thumb {
    width: <?php echo $width; ?>px;
    height: <?php echo $width; ?>px;
    background: url(<?php echo $src; ?>) no-repeat;
	margin: auto;
    border: 3px dotted #ebebeb;
}
</style>
	<input type="hidden" name="xencarousel_nonce" id="xencarousel_nonce" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
	<input type="hidden" name="_xencarousel_image_id" id="xencarouselimageid" value="<?php echo get_post_meta($post, '_xencarousel_image_id', true); ?>" />
	<label for="_xencarousel_image"><strong><?php __("Choose an image from the Media Library:", 'xencarousel' ); ?></strong></label>
	<input type="text" name="_xencarousel_image" id="xencarouselimage" value="<?php echo get_post_meta($post, '_xencarousel_image', true); ?>" style="width: 500px" />
	<p>Or <a id="add_image" class="thickbox" href="media-upload.php?post_id=<?php echo $post; ?>&amp;type=image&amp;TB_iframe=true&amp;width=640&amp;height=322">Upload a new image</a></p>
	<div id="xencarousel_thumb"></div>
	<br clear="both" />
	
<?php
}

function xencarousel_ajax_search()
{
	global $wpdb;
	$sql = "SELECT post_title, id FROM $wpdb->posts WHERE post_type = 'attachment' AND post_mime_type LIKE 'image/%' AND (post_title LIKE '".$_GET['q']."%' OR post_name LIKE '".$_GET['q']."%')";
	$results = $wpdb->get_results($sql,ARRAY_A);
	$images = '';
	if (count($results) > 0) {
		foreach ($results as $result)
		{
			$images .= $result['post_title'] . ' [id: '.$result['id'].']|'.$result['id']."\n";
		}
	} else {
		$images = 'No Results';
	}
	echo $images;
	exit;
}

function xencarousel_ajax_image()
{
    $attachment_id = $_GET['xencarousel_image_id'];
    $image = _xen_get_attachment_image($attachment_id);
    $src = $image[0];
    $width = $image[1];
    $height = $image[2];
    $img = array('img' => urlencode($src), 'w' => $width, 'h' => $height);
    echo json_encode($img);
	exit;
}


function xencarousel_output()
{
	$images = _get_carousel_images();
?>
	<div id="xencarouselcontainer">
    	<div id="xencarousel1" class="xencarousel" rel="xencarousel1">
            <ul>
    		<?php foreach($images as $image): ?>
    	        <li><a href="<?php echo $image['link']; ?>" title="<?php echo $image['title']; ?>"><img src="<?php echo $image['src']; ?>" alt="<?php echo $image['title']; ?>" width="<?php echo $image['width']; ?>" height="<?php echo $image['height']; ?>" /></a></li>
    		<?php endforeach; ?>
    	    </ul>
    	</div>
    	<span class="prev">Previous</span>
        <span class="next">Next</span>
        <div id="xencarouseloverlay"></div>
	</div>
<?php
}


function _xen_get_attachment_image($attachment_id)
{
    $image = wp_get_attachment_image_src($attachment_id,'full');
    return $image;
}

function _save_image_data( $post_id )
{

    global $post;

        if ( !wp_verify_nonce( $_POST['xencarousel_nonce'], plugin_basename(__FILE__) ) )
        {  
            return $post_id;  
        }

        if ( !current_user_can( 'edit_post', $post_id ) )
        {          
            return $post_id;  
        }

        // $firephp->log("post id: ". $post_id);
        
        $_xencarousel_image_id = $_POST['_xencarousel_image_id'];
        $_xencarousel_image = $_POST['_xencarousel_image'];
        
        if ( get_post_meta($post_id, '_xencarousel_image_id') == "" )
        {
            add_post_meta($post_id, '_xencarousel_image_id', $_xencarousel_image_id, true);                
        }
        elseif ($_xencarousel_image_id != get_post_meta($post_id, '_xencarousel_image_id', true) )  
        {
            update_post_meta($post_id, '_xencarousel_image_id', $_xencarousel_image_id);                
        }
        elseif($_xencarousel_image_id == "")
        {
            delete_post_meta($post_id, '_xencarousel_image_id', get_post_meta($post_id, '_xencarousel_image_id', true));                
        }

        if ( get_post_meta($post_id, '_xencarousel_image') == "" )
        {
            add_post_meta($post_id, '_xencarousel_image', $_xencarousel_image, true);                
        }
        elseif ($_xencarousel_image != get_post_meta($post_id, '_xencarousel_image', true) )  
        {
            update_post_meta($post_id, '_xencarousel_image', $_xencarousel_image);                
        }
        elseif($_xencarousel_image == "")
        {
            delete_post_meta($post_id, '_xencarousel_image', get_post_meta($post_id, '_xencarousel_image', true));                
        }


}

function _get_carousel_images()
{
	$images = array();
	
	$carousel_posts = new WP_Query();
	add_filter('posts_join', '_get_custom_field_posts_join');
	add_filter('posts_groupby', '_get_custom_field_posts_group');
	$carousel_posts->query('showposts=5&post_type=any'); //Uses same parameters as query_posts
	remove_filter('posts_join', '_get_custom_field_posts_join');
	remove_filter('posts_groupby', '_get_custom_field_posts_group');	
	
	while ($carousel_posts->have_posts()) : $carousel_posts->the_post();
		$attachment_id = get_post_meta($carousel_posts->post->ID, '_xencarousel_image_id', true);
		$image = _xen_get_attachment_image($attachment_id);
		$images[] = array("src" => $image[0], "width"=> $image[1], "height" => $image[2], "title" => the_title('','',false), "link" => get_permalink($post->ID) );
	endwhile;
	
	return $images;
}



function _get_custom_field_posts_join($join) {
	global $wpdb, $customFields;
	return $join . "  JOIN $wpdb->postmeta postmeta ON (postmeta.post_id = $wpdb->posts.ID and postmeta.meta_key = '_xencarousel_image_id') ";
}

function _get_custom_field_posts_group($group) {
	global $wpdb;
	$group .= " $wpdb->posts.ID ";
	return $group;
}


?>