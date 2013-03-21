<?php 
/**
 * Profile Template
 */
?>
<?php wp_nonce_field( basename(__FILE__), 'wpspin_profile_options_nonce' ); ?>

<p>
  <label for="wpspin-profile-options-twitter"><?php _e( "Twitter Username" ); ?></label>
<br />
<input class="widefat" type="text" name="wpspin-profile-options-twitter" id="wpspin-profile-options-twitter" value="<?php echo esc_attr( get_post_meta( $object->ID, 'wpspin_profile_twitter', true ) ); ?>" size="10" />
</p>
<p>
  <label for="wpspin-profile-options-facebook"><?php _e( "Facebook Username" ); ?></label>
<br />
<input class="widefat" type="text" name="wpspin-profile-options-facebook" id="wpspin-profile-options-facebook" value="<?php echo esc_attr( get_post_meta( $object->ID, 'wpspin_profile_facebook', true ) ); ?>" size="10" />
</p>
<p>
<?php _e( "DJ ID" ) ?>
<br />
<?php echo esc_attr( get_post_meta( $object->ID, '_wpspin_profile_id', true ) ); ?>
</p>
<p>
<?php _e( "Show Name (ID)" ) ?>
<br />
<?php echo self::linkify(esc_attr( get_post_meta( $object->ID, '_wpspin_profile_show_name', true ) )); ?> (<?php echo esc_attr( get_post_meta( $object->ID, '_wpspin_profile_show_id', true ) ); ?>)
</p>
