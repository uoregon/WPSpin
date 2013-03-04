<?php namespace WPSpin;

class SettingsView implements ViewModelInterface
{
  /**
   * __construct
   *
   * @param mixed $callback
   * @access public
   * @return void
   */

  public function __construct($callback, $sanitizeCallback) 
  {
    add_menu_page("Spinitron Integration Settings", "Spinitron", "manage_options", "wpspin-admin", $callback, "", 25);
    register_setting( 'wpspin_setting', 'wpspin_setting_fields', $sanitizeCallback);
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

    include __DIR__ . "/../views/settings.php";
  }


}

?>
