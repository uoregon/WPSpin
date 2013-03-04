<?php
/**
 * Show Metabox
 *
 */
?>

<?php wp_nonce_field( basename( __FILE__ ), 'wpspin_show_options_nonce' ); ?>

<table>
<tr>
<td>
  <label for="wpspin-show-options-showurl"><?php _e( "Show URL" ); ?></label>
</td>
<td>
<input class="widefat" type="text" name="wpspin-show-options-showurl" id="wpspin-show-options-showurl" value="<?php echo esc_attr( get_post_meta( $object->ID, 'wpspin_show_showurl', true ) ); ?>" size="30" />
</td>
</tr>
<tr>
  <td><?php _e( "Show ID" ) ?></td>
  <td><?php echo esc_attr( get_post_meta( $object->ID, '_wpspin_show_id', true ) ) ?> </td> 
</tr>
<tr>
  <td><?php _e( "DJs" ) ?></td>
  <td><?php echo self::linkify(esc_html( self::getDJNames($object->ID) )) ?></td>
</tr>
</table>
