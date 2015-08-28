<?php
/*
Plugin Name: masonry flickr photo plugin
Plugin URI:  http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Plugin de Fab pour insertion des photos d'un album flickr
Version:     0.1
Author:      Fabrice SABOT
Author URI:  http://URI_Of_The_Plugin_Author
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: my-toolset
*/


class Mfpp_Plugin {
	public function __construct()
    {
		add_shortcode( 'mfpp_album_flickr', 'mfpp_shortcode_album_flickr' );
        
    }

	require('FlickrApi.php');

	function mfpp_photosets_getPhotos($photoset_id) {

	    $flickr = new FlickrApi('1beeea1bc67afa38c45c9d7066effe76');

	    $params = array(
	        'method'    => 'flickr.photosets.getPhotos',
	        'photoset_id' => $photoset_id,
	        'user_id' => '132976700@N06',
	        'extras' => 'url_l,url_m',
	        'per_page' => 100
	        
	    );
	    $photos = $flickr->api($params); // get photos
	    return $photos;
	}




	// Shortcodes
	function mfpp_shortcode_album_flickr($atts){
		$retval ="";	
		extract( shortcode_atts( array(
			'album' => 'undefined'
	 	), $atts ) );
		
		$res = mfpp_photosets_getPhotos($album);//'72157657402272560'
		$photos = $res['photoset']['photo'];
		shuffle($photos);
		
		$retval .='<div id="container" >';
	  	$retval .='<div id="photos" class="loading">';
	  	<?php
	    foreach ($photos as $photo) {
	      if(isset($photo['url_l'])) {
	          $retval .='<a class="photo fancybox" rel="flickr" title="'.$photo['title'].'" href="'.$photo['url_l'].'">'.
	                  '<img src="'.$photo['url_m'].'" alt="'.$photo['title'].'"  />'.
	                  '<span class="title">'.$photo['title'].'</span>'.
	                '</a>';
	      }
	    }

	  ?>

		$retval .='</div>';  
		$retval .='</div>';
		return $retval;

	}
}



new Mfpp_Plugin();