<?php 
/* load plugin */
add_action( 'admin_init', 'DYFCF7_load_plugin', 11 );
function DYFCF7_load_plugin() {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( ! ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) ) {
		add_action( 'admin_notices','DYFCF7_install_error' );
	}
}

/* instal error */
function DYFCF7_install_error() {
    deactivate_plugins( DYFCF7_BASE_NAME );?>
 		<div class="error">
    		<p>
       			<?php _e( ' cf7 calculator plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=contact+form+7">Contact Form 7</a> plugin installed and activated.', 'dynamic-text-and-field-for-contact-form-7' ); ?>
    		</p>
 		</div>
	<?php
}
