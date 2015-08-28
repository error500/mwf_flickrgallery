<?php
add_action( 'admin_menu', 'mfpp_add_admin_menu' );
add_action( 'admin_init', 'mfpp_settings_init' );


function mfpp_add_admin_menu(  ) { 

	add_options_page( 'masonryflickrphotoplugin', 'masonryflickrphotoplugin', 'manage_options', 'masonryflickrphotoplugin', 'masonryflickrphotoplugin_options_page' );

}


function mfpp_settings_init(  ) { 

	register_setting( 'pluginPage', 'mfpp_settings' );

	add_settings_section(
		'mfpp_pluginPage_section', 
		__( 'Your section description', 'languages' ), 
		'mfpp_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'mfpp_text_field_0', 
		__( 'Settings field description', 'languages' ), 
		'mfpp_text_field_0_render', 
		'pluginPage', 
		'mfpp_pluginPage_section' 
	);


}


function mfpp_text_field_0_render(  ) { 

	$options = get_option( 'mfpp_settings' );
	?>
	<input type='text' name='mfpp_settings[mfpp_text_field_0]' value='<?php echo $options['mfpp_text_field_0']; ?>'>
	<?php

}


function mfpp_settings_section_callback(  ) { 

	echo __( 'This section description', 'languages' );

}


function mfpp_options_page(  ) { 

	?>
	<form action='options.php' method='post'>
		
		<h2>masonryflickrphotoplugin</h2>
		
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
		
	</form>
	<?php

}

?>