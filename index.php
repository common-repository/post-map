<?php

/*
  Plugin Name: Post Map
  Plugin URI: http://jaffamonkey.com
  Description: Add coordinates post meta based on location, and display map widget in sidebar.
  Version: 0.1
  Author: jaffamonkey
  Author URI: http://jaffamonkey.com
  License: GPL2
 */
require ('mapwidget.php');
class add_coords {
  function addcoords($post_ID)  {
 	$address = $_POST["postcode"];
        $prepAddr = str_replace(' ','+',$address);
        $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
        $output= json_decode($geocode);
        $latitude = $output->results[0]->geometry->location->lat;
        $longitude = $output->results[0]->geometry->location->lng;
        add_post_meta($post_ID, 'post_latitude', $latitude, true);
        add_post_meta($post_ID, 'post_longitude', $longitude, true);
        add_post_meta($post_ID, 'postcode', $address, true);
}}

function my_post_options_box() {
add_meta_box('location', 'Post Location', 'custom_post_info', 'post', 'side', 'high');
}

function custom_post_info() {
global $post;
?>
<fieldset id="mycustom-div">
<div>
<p>
<label for="postcode">Enter postcode:</label><br />
<input type="text" name="postcode" id="postcode" value="<?php echo get_post_meta($post->ID, 'postcode', true); ?>">
</p>
</div>
</fieldset>
<?php
}

$myCoordsClass = new add_coords();
add_action('publish_post', array($myCoordsClass, 'addcoords'));
add_action('admin_menu', 'my_post_options_box');
add_action('wp_head', '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>');
?>