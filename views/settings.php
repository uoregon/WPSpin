<?php
/**
 * Settings Page Template
 */
?>
<div class="wrap">
   <div id="icon-themes" class="icon32"><br /></div>
   <?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
        <div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
   <?php endif; ?>
   <?php if ( isset($_SESSION['wpspin-exception']) ) : ?>
         <div class="error fade"><p><strong><?php echo $_SESSION['wpspin-exception'] ?></strong></p></div>
         <?php unset($_SESSION['wpspin-exception']) ?>
   <?php endif; ?>
   <h2>WP Spin Settings</h2>
   <form method="post" action="options.php">
   <?php settings_fields('wpspin_setting') ?>
      <table class="form-table">
         <tr valign="top">
         <th scope="row"><?php _e('API Key') ?></th>
            <td>
            <input id="wpspin_setting_fields[apikey]" class="regular-text" type="text" name="wpspin_setting_fields[apikey]" value="<?php esc_attr_e($this->getApiInput()) ?>" />
               <label class="description" for="wpspin_setting_fields[apikey]"><?php _e('SpinPAPI API Key') ?></label>
            </td>
         </tr>
         <tr valign="top">
         <th scope="row"><?php _e('Secret Key') ?></th>
            <td>
               <input id="wpspin_setting_fields[secretkey]" class="regular-text" type="text" name="wpspin_setting_fields[secretkey]" value="<?php esc_attr_e($this->getSecretInput()) ?>" />
               <label class="description" for="wpspin_setting_fields[secretkey]"><?php _e('SpinPAPI Secret Key') ?></label>
            </td>
         </tr>
         <tr valign="top">
         <th scope="row"><?php _e('Station Name') ?></th>
            <td>
            <input id="wpspin_setting_fields[station]" class="regular-text" type="text" name="wpspin_setting_fields[station]" value="<?php esc_attr_e($this->getStationInput()) ?>" />
               <label class="description" for="wpspin_setting_fields[station]"><?php _e("Station's ID name e.g. lower case callsign 'kwva'") ?></label>
            </td>
         </tr>
         <tr valign="top"><th scope="row"><?php _e( 'Select Timezone' ); ?></th>
              <td>
                <select name="wpspin_setting_fields[timezone]">
                  <?php $this->generateOptions($this->getTimezonesArray(), $this->getTimezoneInput()); ?>
                </select>
                <label class="description" for="wpspin_setting_fields[timezone]"><?php _e( 'Select Timezone for SpinPAPI' ); ?></label>
              </td>
         </tr>
         <tr valign="top"><th scope="row"><?php _e( 'Import Show & DJ Data' ); ?></th>
           <td>
             <input id="wpspin_setting_fields[import]" name="wpspin_setting_fields[import]" type="checkbox" value="1" />
             <label class="description" for="wpspin_setting_fields[import]"></label>
           </td>
         </tr>
      </table>
      <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Options') ?>" />
      </p>
   </form>
</div>
