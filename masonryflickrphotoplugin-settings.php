<?php
add_action( 'admin_menu', 'mfpp_add_admin_menu' );
add_action( 'admin_init', 'mfpp_settings_init' );


function mfpp_add_admin_menu(  ) { 

	add_submenu_page( 'tools.php', 'masonryflickrphotoplugin', 'Options masonry Flickr photo plugin', 'manage_options', 'masonryflickrphotoplugin', 'mfpp_options_page' );

}


function mfpp_settings_init(  ) { 

	register_setting( 'pluginPage', 'mfpp_settings' );

	add_settings_section(
		'mfpp_pluginPage_section', 
		__( 'Connexion à votre compte Flickr', 'wordpress' ), 
		'mfpp_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'mfpp_flickrKey', 
		__( 'Flickr API code', 'wordpress' ), 
		'mfpp_flickrKey_render', 
		'pluginPage', 
		'mfpp_pluginPage_section' 
	);

	add_settings_field( 
		'mfpp_flickrUserID', 
		__( 'Flickr User ID', 'wordpress' ), 
		'mfpp_flickrUserID_render', 
		'pluginPage', 
		'mfpp_pluginPage_section' 
	);


}


function mfpp_flickrKey_render(  ) { 

	$options = get_option( 'mfpp_settings' );
	?>
	<input type='text' name='mfpp_settings[mfpp_flickrKey]' value='<?php echo $options['mfpp_flickrKey']; ?>'>
	<?php

}


function mfpp_flickrUserID_render(  ) { 

	$options = get_option( 'mfpp_settings' );
	?>
	<input type='text' name='mfpp_settings[mfpp_flickrUserID]' value='<?php echo $options['mfpp_flickrUserID']; ?>'>
	<?php

}


function mfpp_settings_section_callback(  ) { 

	echo __( 'Dans cette section, vous pourrez connecter votre compte Flickr', 'wordpress' );
	echo '<br><a href="https://www.flickr.com/services/apps/create/apply/">Création de la cle API flickr</a>';
	echo '<br><a href="http://idgettr.com//">POur retrouver votre User ID</a>';
	echo 'Utilisation : Il vous suffit d\'ajouter le shortcode [mfpp_album_flickr  photoset_id=xxxxxxxxxxxxx]';
	echo '<h4>options :</h4>';
	echo 'photoset_id (obligatoire) : le numéro de l\'album flicker à publier. On le trouve sur l\'url comme ici par exemple https://www.flickr.com/photos/ohcl/albums/72157657402272560';
	

}


function mfpp_options_page(  ) { 

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