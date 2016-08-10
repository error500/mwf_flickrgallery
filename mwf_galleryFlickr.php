<?php
/*
Plugin Name: Wordpress plugin to display a gallery for flickr photo album
Plugin URI:  https://github.com/error500/mwf_galleryflickr
Description: Plugin that enable a shortcode for display a flikr album
Version:     0.1
Author:      Error500
Author URI:  https://github.com/error500
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages

*/


class Mwf_galleryflickr {
	public function __construct()
    {
    	$options = get_option( 'mwf_settings' );
    	$this->userId = $options['mwf_flickrUserID'];
    	
    	include_once plugin_dir_path( __FILE__ ).'/FlickrApi.php';
		include_once plugin_dir_path( __FILE__ ).'mwf_galleryFlickr-settings.php';
	    
	    $this->flickr = new FlickrApi($options['mwf_flickrKey']);
    	add_shortcode( 'mwf_photoset_flickr', array($this, 'mwf_shortcode_photoset_flickr') );
        
    }



	function mwf_photosets_getPhotos($photosetId) {


	    $params = array(
	        'method'    => 'flickr.photosets.getPhotos',
	        'photoset_id' => $photosetId,
	        'user_id' => $this->userId ,
	        'extras' => 'url_l,url_m',
	        'per_page' => 100
	        
	    );
	    $photos = $this->flickr->api($params); // get photos
	    
	    return $photos;
	}




	// Shortcodes
	function mwf_shortcode_photoset_flickr($atts){
		$retval ="";	
		extract( shortcode_atts( array(
			'photoset_id' => 'undefined'
	 	), $atts ) );
		
		$flickrApiResponse = $this->mwf_photosets_getPhotos($photoset_id);
		$photos = $flickrApiResponse['photoset']['photo'];
		shuffle($photos);
		
		
		$retval .='<div id="mwf_container" >';
	  	$retval .='<div id="mwf_photos" class="loading">';
	  	
	    foreach ($photos as $photo) {
	      if(isset($photo['url_l'])) {
	          $retval .='<a class="mwf_photo fancybox" rel="mwf" title="'.$photo['title'].'" href="'.$photo['url_l'].'">'.
	                  '<img src="'.$photo['url_m'].'" alt="'.$photo['title'].'"  />'.
	                  '<span class="title">'.$photo['title'].'</span>'.
	                '</a>';
	      }
	    }

		$retval .='</div>';  
		$retval .='</div>';
		$this->mwf_enqueue_scripts();
		
		return $retval;

	}

	function mwf_enqueue_scripts() {
		

		wp_enqueue_style(  'mwf', plugins_url('/css/mwf_gallery.css',__FILE__ ));
		wp_enqueue_style(  'fancybox', plugins_url('/js/fancybox/jquery.fancybox.css',__FILE__ ));
		wp_register_script( 'images-loaded', plugins_url('/js/imagesloaded/imagesloaded.pkgd.min.js',__FILE__),null,null, true );
		wp_register_script( 'fancybox', plugins_url('/js/fancybox/jquery.fancybox.pack.js',__FILE__) ,null,null, true );
		
		wp_enqueue_script(
			'mwf-script',
			plugins_url('/js/mwf_script.js',__FILE__),
			array( 'jquery','images-loaded','fancybox','jquery-masonry' ),null,true);
	}
}



new Mwf_galleryflickr();

