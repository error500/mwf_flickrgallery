<?php
add_action( 'admin_menu', 'mwf_add_admin_menu' );
add_action( 'admin_init', 'mwf_settings_init' );


function mwf_add_admin_menu(  ) { 

	add_submenu_page( 'tools.php', 'masonryflickrphotoplugin', 'Options masonry Flickr photo plugin', 'manage_options', 'masonryflickrphotoplugin', 'mwf_options_page' );

}


function mwf_settings_init(  ) { 

	register_setting( 'pluginPage', 'mwf_settings' );

	add_settings_section(
		'mwf_pluginPage_section', 
		__( 'Connexion à votre compte Flickr', 'wordpress' ), 
		'mwf_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'mwf_flickrKey', 
		__( 'Flickr API code', 'wordpress' ), 
		'mwf_flickrKey_render', 
		'pluginPage', 
		'mwf_pluginPage_section' 
	);

	add_settings_field( 
		'mwf_flickrUserID', 
		__( 'Flickr User ID', 'wordpress' ), 
		'mwf_flickrUserID_render', 
		'pluginPage', 
		'mwf_pluginPage_section' 
	);


}


function mwf_flickrKey_render(  ) { 

	$options = get_option( 'mwf_settings' );
	?>
	<input type='text' name='mwf_settings[mwf_flickrKey]' value='<?php echo $options['mwf_flickrKey']; ?>'>
	<?php

}


function mwf_flickrUserID_render(  ) { 

	$options = get_option( 'mwf_settings' );
	?>
	<input type='text' name='mwf_settings[mwf_flickrUserID]' value='<?php echo $options['mwf_flickrUserID']; ?>'>
	<?php

}


function mwf_settings_section_callback(  ) { 

	echo __( 'Dans cette section, vous pourrez connecter votre compte Flickr', 'wordpress' );
	echo '<br><a href="https://www.flickr.com/services/apps/create/apply/">Création de la cle API flickr</a>';
	echo '<br><a href="http://idgettr.com//">Pour retrouver votre User ID</a>';
	echo 'Utilisation : Il vous suffit d\'ajouter le shortcode [mwf_album_flickr  photoset_id=xxxxxxxxxxxxx]';
	echo '<h4>options :</h4>';
	echo 'photoset_id (obligatoire) : le numéro de l\'album flicker à publier. On le trouve sur l\'url comme ici par exemple https://www.flickr.com/photos/ohcl/albums/72157657402272560';
	

}


function mwf_options_page(  ) { 

	?>
	<form action='options.php' method='post'>
		
		<h2>Paramétrage de masonry flickr photo plugin</h2>
		
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
		
	</form>
	<?php

}

?>