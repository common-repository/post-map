<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author pablo
 */

add_action("widgets_init", array('Post_map', 'register'));
class Post_map {
  function control(){
  $data = get_option('post_map');
  echo '<p><label>Map width<input name="post_map_option1"
type="text" value="'.$data['option1'].'" /></label></p>
  <p><label>Map height<input name="post_map_option2"
type="text" value="'.$data['option2'].'" /></label></p>
 <p><label>Map zoom level<input name="post_map_option3"
type="text" value="'.$data['option3'].'" /></label></p>';
   if (isset($_POST['post_map_option1'])){
    $data['option1'] = attribute_escape($_POST['post_map_option1']);
    $data['option2'] = attribute_escape($_POST['post_map_option2']);
    $data['option3'] = attribute_escape($_POST['post_map_option3']);
    update_option('post_map', $data); 
  }
}

  function widget($args){
    global $post;
    $result = get_option('post_map');
    $mapzoom = $result ['option3'];
    $mapwidth = $result ['option1'];
    $mapheight = $result ['option2'];
    $postlatitude2 = get_post_meta($post->ID, 'post_latitude', true);
    $postlongitude2 = get_post_meta($post->ID, 'post_longitude', true);
    echo $args['before_widget'];
    echo $args['before_title'] . 'Location' . $args['after_title'];
    echo '<div id="map" style="width:'.$mapwidth.'px;height: '.$mapheight.'px;"></div> 

   <script type="text/javascript"> 
      var myOptions = {
         zoom: '.$mapzoom.',
         center: new google.maps.LatLng('.$postlatitude2.', '.$postlongitude2.'),
         mapTypeId: google.maps.MapTypeId.ROADMAP,
           };
         var map = new google.maps.Map(document.getElementById("map"), myOptions);
         var marker = new google.maps.Marker({position: new google.maps.LatLng('.$postlatitude2.', '.$postlongitude2.'),map: map})
   </script> ';
    echo $args['after_widget'];
  }
  function register(){
    register_sidebar_widget('Post Map', array('post_map', 'widget'));
    register_widget_control('Post Map', array('post_map', 'control'));
  }
}
?>