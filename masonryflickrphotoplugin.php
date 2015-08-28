<?php
/*
Plugin Name: masonry flickr photo plugin
Plugin URI:  http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Plugin that enable a shortcode for display 
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
    	$options = get_option( 'mfpp_settings' );
    	$this->userId = $options['mfpp_flickrUserID'];
    	
    	include_once plugin_dir_path( __FILE__ ).'/FlickrApi.php';
		include_once plugin_dir_path( __FILE__ ).'masonryflickrphotoplugin-settings.php';
	    
	    $this->flickr = new FlickrApi($options['mfpp_flickrKey']);
    	add_shortcode( 'mfpp_photoset_flickr', array($this, 'mfpp_shortcode_photoset_flickr') );
        
    }



	function mfpp_photosets_getPhotos($photosetId) {


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
	function mfpp_shortcode_photoset_flickr($atts){
		$retval ="";	
		extract( shortcode_atts( array(
			'photoset_id' => 'undefined'
	 	), $atts ) );
		
		$flickrApiResponse = $this->mfpp_photosets_getPhotos($photoset_id);
		$photos = $flickrApiResponse['photoset']['photo'];
		shuffle($photos);
		
		
		$retval .='<div id="mfpp_container" >';
	  	$retval .='<div id="mfpp_photos" class="loading">';
	  	
	    foreach ($photos as $photo) {
	      if(isset($photo['url_l'])) {
	          $retval .='<a class="mfpp_photo fancybox" rel="mfpp" title="'.$photo['title'].'" href="'.$photo['url_l'].'">'.
	                  '<img src="'.$photo['url_m'].'" alt="'.$photo['title'].'"  />'.
	                  '<span class="title">'.$photo['title'].'</span>'.
	                '</a>';
	      }
	    }

		$retval .='</div>';  
		$retval .='</div>';
		$this->mfpp_enqueue_scripts();
		
		return $retval;

	}

	function mfpp_enqueue_scripts() {
		
		wp_enqueue_style(  'mfpp', plugins_url('/css/mfpp.css',__FILE__ ));
		wp_enqueue_style(  'fancybox', plugins_url('/bower_components/fancybox/source/jquery.fancybox.css',__FILE__ ));
		wp_register_script( 'images-loaded', plugins_url('/bower_components/imagesloaded/imagesloaded.pkgd.min.js',__FILE__),null,null, true );
		wp_register_script( 'fancybox', plugins_url('/bower_components/fancybox/source/jquery.fancybox.pack.js',__FILE__) ,null,null, true );
		
		wp_enqueue_script(
			'mfpp-script',
			plugins_url('/js/mfpp_script.js',__FILE__),
			array( 'jquery','images-loaded','fancybox','jquery-masonry' ),null,true);
	}
}


// Masonry doesn't work with embeded jQuery version... so i change it here
if( !is_admin() ){
	wp_deregister_script('jquery');
	wp_register_script('jquery', plugins_url('bower_components/jquery/dist/jquery.min.js',__FILE__), null,null,true );
	wp_enqueue_script('jquery');
}


new Mfpp_Plugin();
