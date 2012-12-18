<?php namespace WPSpin;

class SettingsView implements ViewModelInterface
{
  public function __construct($callback) 
  {
    add_menu_page("Spinitron Integration Settings", "Spinitron", "manage_options", "wpspin-admin", $callback, "", 25);
    register_setting( 'wpspin_setting', 'wpspin_setting_fields', '');
  }

  private function getApiInput()
  {
    $options = get_option('wpspin_setting_fields');
    return $options['apikey'];
  }

  private function getSecretInput()
  {
    $options = get_option('wpspin_setting_fields');
    return $options['secretkey'];
  }

  private function getStationInput()
  {
    $options = get_option('wpspin_setting_fields');
    return $options['station'];
  }

  private function getTimezoneInput()
  {
    $options = get_option('wpspin_setting_fields');
    return $options['timezone'];
  }

  private function getTimezonesArray()
  {
    $regions = array(
          'Africa' => \DateTimeZone::AFRICA,
          'America' => \DateTimeZone::AMERICA,
          'Antarctica' => \DateTimeZone::ANTARCTICA,
          'Asia' => \DateTimeZone::ASIA,
          'Atlantic' => \DateTimeZone::ATLANTIC,
          'Europe' => \DateTimeZone::EUROPE,
          'Indian' => \DateTimeZone::INDIAN,
          'Pacific' => \DateTimeZone::PACIFIC
        );
    $tzlist = array();
    foreach ($regions as $name => $mask) {
      $tzlist = array_merge($tzlist, \DateTimeZone::listIdentifiers($mask));
    }
    return $tzlist;
  }

  private function generateOptions($option, $selected)
  {
    $selectedOption = '';
    $unselectedOption = '';

    foreach ( $option as $timezone ) {
      $label = $timezone;
      if ( $selected == $timezone ) // Make default first in list
        $selectedOption = "\n\t<option style=\"padding-right: 10px;\" selected='selected' value='" . esc_attr( $timezone ) . "'>$label</option>";
      else
        $unselectedOption .= "\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr( $timezone ) . "'>$label</option>";
    }
    echo $selectedOption . $unselectedOption;

  }

  public function render()
  {
    if ( ! isset( $_REQUEST['settings-updated'] ) )
    {
      $_REQUEST['settings-updated'] = false;
    }

//Begin Render Template ?>


<div class="wrap">
   <div id="icon-themes" class="icon32"><br /></div>
   <?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
        <div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
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
      </table>
      <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Options') ?>" />
      </p>
   </form>
</div>

<?php //End Render Template
  }



}

?>
