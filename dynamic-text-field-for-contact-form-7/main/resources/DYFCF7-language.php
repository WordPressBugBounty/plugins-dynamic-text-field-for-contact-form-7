<?php 
/*;paded contact form 7 */
add_action( 'plugins_loaded', 'DYFCF7_load_textdomaindd_pro' );
function DYFCF7_load_textdomaindd_pro() {
    load_plugin_textdomain( 'dynamic-text-field-for-contact-form-7', false, dirname( DYFCF7_BASE_NAME ) . '/languages' ); 
}

/*;paded contact form 7 */
function DYFCF7_load_my_own_textdomaindd_pro( $mofile, $domain ) {
    if ( 'dynamic-text-field-for-contact-form-7' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
        $locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
        $mofile = WP_PLUGIN_DIR . '/' . dirname( DYFCF7_BASE_NAME ) . '/languages/' . $domain . '-' . $locale . '.mo';
    }
    return $mofile;
}
add_filter( 'load_textdomain_mofile', 'DYFCF7_load_my_own_textdomaindd_pro', 10, 2 );