<?php
/**
** A base module for the following types of tags:
** 	[dynamic-text-field]  # dynamic-text-field
**/


/* tag shortcodetext field */
add_action( 'wpcf7_init', 'DYFCF7_add_form_tag_shortcodefield', 10, 0 );
function DYFCF7_add_form_tag_shortcodefield() {
	wpcf7_add_form_tag( array( 'shortcodefield', 'shortcodefield*' ),
		'DYFCF7_shortcodefield_form_tag_handler', array( 'name-attr' => true) );
}


/*shortcode hidden*/
add_action( 'wpcf7_init', 'DYFCF7_shortcodehidden', 10, 0 );
function DYFCF7_shortcodehidden() {
	wpcf7_add_form_tag( array( 'shortcodehidden' , 'shortcodehidden*' , 'shortcodehidden' ),
		'DYFCF7_shortcodefield_form_tag_handler', true );
} 


/* tag Handler */
function DYFCF7_shortcodefield_form_tag_handler( $tag ) {
	if ( empty( $tag->name ) ) {
		return '';
	}
	
	$validation_error = wpcf7_get_validation_error( $tag->name );
	$class = wpcf7_form_controls_class( $tag->type );
	$class .= ' wpcf7-validates-as-shortcodefield';
	$atts = array();
	$atts['class'] = $tag->get_class_option( $class );
	$atts['id'] = $tag->get_id_option();

	$value = (string) reset( $tag->values );

	$value = $tag->get_default_option( $value );

	$value = wpcf7_get_hangover( $tag->name, $value );

	$shorcodeval = do_shortcode('['.$value.']');
	if( $shorcodeval != '['.$value.']' ){
		$value = esc_attr( $shorcodeval );
	}
	
	if($tag->basetype == "shortcodefield"){ 

		$atts['type'] = 'text'; 

	}else if($tag->basetype == "shortcodehidden"){

		$atts['type'] = 'hidden';

	}else{

		$atts['type'] = 'text';
	}

	$atts['value'] = $value;

	$atts['name'] = $tag->name;

	$atts = wpcf7_format_atts($atts);
	// echo "<pre>";
	// print_r($atts);
	// echo "</pre>";

	$html = sprintf(
		'<span class="wpcf7-form-control-wrap %1$s"><input %2$s />%3$s</span>',
		sanitize_html_class( $tag->name ), $atts, $validation_error );

	return $html;
}

/* shortcode - get post info */
function DYFCF7_get_post_title($atts){
	$args = shortcode_atts( 
	    array(
	        'key' => 'post_title',
	    ), 
	    $atts
	);

	global $post;
	// echo "<pre>";
	// print_r($args['key']);
	// echo "</pre>";

	$key = $args['key'];
	$val = $post->$key;
	return $val;
}
add_shortcode('DYFCF7_get_post_title', 'DYFCF7_get_post_title');

/* shortcode - get current user data */
function DYFCF7_get_current_user($atts)
{ 
	$args = shortcode_atts( 
	    array(
	        'key' => 'user_login',
	    ), 
	    $atts
	);
	// echo "<pre>";
	// print_r($args['key']);
	// echo "</pre>";
	$val = '';
	if (is_user_logged_in()) {
	 	$current_user = wp_get_current_user();
	 	// echo "<pre>";
		// print_r($current_user);
		// echo "</pre>";
	 	$key = $args['key'];
	 	$val = $current_user->$key;
	}

	return $val;
}
add_shortcode('DYFCF7_get_current_user', 'DYFCF7_get_current_user');

/* shortcode - get custom field values */
function DYFCF7_get_custom_field($atts)
{
	$args = shortcode_atts( 
	    array(
	        'key' => '',
	        'post_id' => -1,
	    ), 
	    $atts
	);
	global $post;
	// echo "<pre>";
	// print_r($post);
	// echo "</pre>";
	//echo $args['key'];
	$key = $args['key'];
	if (!empty($key)) {
		$val = get_post_meta($post->ID ,$key,true);
	}else{
		$val = '';
	}
	

	return $val;
}
add_shortcode('DYFCF7_get_custom_field', 'DYFCF7_get_custom_field');

/* shortcode - get page Url */
function DYFCF7_page_url($atts){
	global $post;
	// echo "<pre>";
	// print_r($post->guid);
	// echo "</pre>";
	$val = $post->guid;

	return $val;
}
add_shortcode('DYFCF7_page_url', 'DYFCF7_page_url');

/* shortcode - get woocommerece product data */
function DYFCF7_get_product($atts){
	$args = shortcode_atts( 
	    array(
	        'key' => 'name',
	    ), 
	    $atts
	);
	global $product;
	$val = '';
	if(is_shop() || is_product() || is_cart() || is_checkout() || is_product_category()){
	$product_data = $product->get_data();
	// echo "<pre>";
	// print_r($product_data['id']);
	// echo "</pre>";
	$key = $args['key'];
	$val = $product_data[$key];
	}
	return $val;

}
add_shortcode('DYFCF7_get_product', 'DYFCF7_get_product');

/* shortcode - show bolg-info data */
function DYFCF7_get_bloginfo($atts){
	$args = shortcode_atts( 
	    array(
	        'show' => 'name',
	    ), 
	    $atts
	);
	$key = $args['show'];
	$val = get_bloginfo($key);

	// echo "<pre>";
	// print_r($val);
	// echo "</pre>";
	return $val;
}
add_shortcode('DYFCF7_get_bloginfo', 'DYFCF7_get_bloginfo');

/* Tag generator for Shortcodefield field*/ 
function DYFCF7_tag_generator_shortcodefield($contact_form, $args = ''){
	$args = wp_parse_args( $args, array() );
	$wpcf7_contact_form = WPCF7_ContactForm::get_current();
	$contact_form_tags = $wpcf7_contact_form->scan_form_tags();
	$type = sanitize_text_field($args['id']);
	?>
	<div class="control-box">
		<fieldset>
			<table class="form-table">
				<tbody>
					<?php if($type == "shortcodefield"){ ?>
						<tr>
							<th scope="row"><?php echo esc_html( __( 'Field type', 'dynamic-text-field-for-contact-form-7' ) ); ?></th>
							<td>
								<fieldset>
								<legend class="screen-reader-text"><?php echo esc_html( __( 'Field type', 'dynamic-text-field-for-contact-form-7' ) ); ?></legend>
								<label><input type="checkbox" name="required" /> <?php echo esc_html( __( 'Required field', 'dynamic-text-field-for-contact-form-7' ) ); ?></label>
								</fieldset>
							</td>
						</tr>
					<?php } ?>
					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'dynamic-text-field-for-contact-form-7' ) ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', 'dynamic-text-field-for-contact-form-7' ) ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'dynamic-text-field-for-contact-form-7' ) ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'dynamic text field', 'dynamic-text-field-for-contact-form-7' ) ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="values" class="oneline" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>" />
							<?php _e( 'Add Here Any Shortcode', 'dynamic-text-field-for-contact-form-7' ); ?> <br>
							<?php _e( 'remove square brackets <code> [] </code> ' , 'dynamic-text-field-for-contact-form-7' ); ?> <br>
							<?php _e( 'so ex.<code> [shortcode attribute=\'value\'] </code>  add like this  <code> shortcode attribute=\'value\' </code>', 'dynamic-text-field-for-contact-form-7' ); ?>
							<?php _e( 'and on attribut value not use single Quotes not use double Quotes ', 'dynamic-text-field-for-contact-form-7' ); ?><br><br>
							<?php _e( "ex.<code>DYFCF7_get_post_title key='post_name'</code>", 'dynamic-text-field-for-contact-form-7' ); ?><br><br>
							<?php _e( "ex.<code>DYFCF7_get_bloginfo show='url'</code>", 'dynamic-text-field-for-contact-form-7' ); ?><br><br>

							<?php _e( '<code><strong>Example Like : You can add this function in functions.php and then you check your value.<br><br>function cityname_demo_shortcode($attr) { <br>
  
									&nbsp&nbsp&nbsp// Things that you want to do.<br>
									&nbsp&nbsp&nbsp$message = "My city name is ".$attr[\'city\'];<br>
									  
									&nbsp&nbsp&nbsp// Output needs to be return<br>
									&nbsp&nbsp&nbspreturn $message;<br>
								}<br>
								// register shortcode<br>
								add_shortcode(\'cityname\', \'cityname_demo_shortcode\');</code></strong>', 'dynamic-text-field-for-contact-form-7' ); ?><br><br>
							<?php _e( '<code><strong>Ex :  shortcode = cityname, attribute = city, and value = any city name.<br><br>cityname city=\'surat\'<br><br>Return value in dynamic field is \'My city name is surat\'</strong></code>', 'dynamic-text-field-for-contact-form-7' ); ?>
						</td>
					</tr>
				</tbody>
			</table>	
		</fieldset>
	</div>

	<div class="insert-box">
		<input type="text" name="<?php echo esc_attr($type); ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

		<div class="submitbox">
		<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'dynamic-text-field-for-contact-form-7' ) ); ?>" />
		</div>

		<br class="clear" />

		<p class="description mail-tag"><label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'dynamic-text-field-for-contact-form-7' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label></p>
	</div>
	<?php
}


/* Tag generator shortcodefield*/
add_action( 'wpcf7_admin_init', 'DYFCF7_add_tag_generator_shortcodefield', 18, 0 );
function DYFCF7_add_tag_generator_shortcodefield() {
	$tag_generator = WPCF7_TagGenerator::get_instance();
	$tag_generator->add( 'shortcodefield', __( 'shortcodefield', 'dynamic-text-field-for-contact-form-7' ),
		'DYFCF7_tag_generator_shortcodefield' );
}


/* Tag generator shortcodehidden*/
add_action( 'wpcf7_admin_init', 'DYFCF7_add_tag_generator_shortcodehidden', 18, 0 );
function DYFCF7_add_tag_generator_shortcodehidden() {
	$tag_generator = WPCF7_TagGenerator::get_instance();
	$tag_generator->add( 'shortcodehidden', __( 'shortcodehidden', 'dynamic-text-field-for-contact-form-7' ),
		'DYFCF7_tag_generator_shortcodefield' );
}


/* validation of text field of shortcode*/
add_filter( 'wpcf7_validate_shortcodefield*', 'DYFCF7_shortcodefield_validation_filter', 10, 2 );
function DYFCF7_shortcodefield_validation_filter( $result, $tag ) {
	$tag = new WPCF7_FormTag( $tag );
	$name = $tag->name;
	$nameeee = sanitize_text_field($_POST[$name]);
	$value = isset( $nameeee )
		? trim( wp_unslash( strtr( (string) $nameeee, "\n", " " ) ) )
		: '';

	if ( 'shortcodefield' == $tag->basetype ) {
		if ( $tag->is_required() && '' == $value ) {
			$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
		}
	}
	return $result;
}