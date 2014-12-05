<?php namespace WPSpin;
/**
 * Setup function. Creates required table if not found.
 *
 * @global type $wpdb
 */

class SettingsModel extends ModelAbstract
{

  public static function getApiKey() {
    $options = get_option('wpspin_setting_fields');
    return $options['apikey'];
  }

  public static function setApiKey($value) {
    $options = get_option('wpspin_setting_fields');
    $options['apikey'] = $value;
    update_option( 'wpspin_setting_fields', $options );
  }

  public static function getSecretKey() {
    $options = get_option('wpspin_setting_fields');
    return $options['secretkey'];
  }

  public static function setSecretKey($value) {
    $options = get_option('wpspin_setting_fields');
    $options['secretkey'] = $value;
    update_option( 'wpspin_setting_fields', $options );
  }

  public static function getStation() {
    $options = get_option('wpspin_setting_fields');
    return $options['station'];
  }

  public static function setStation($value) {
    $options = get_option('wpspin_setting_fields');
    $options['station'] = $value;
    update_option( 'wpspin_setting_fields', $options );
  }

  public static function getTimezone()
  {
    $options = get_option('wpspin_setting_fields');

    // Give default timezone if option hasn't been set yet
    if(!$options)
    {
      return 'America/Los_Angeles';
    }

    return $options['timezone'];
  }

  public static function setTimezone($value)
  {
    $options = get_option('wpspin_setting_fields');
    $options['timezone'] = $value;
    update_option( 'wpspin_setting_fields', $options );
  }
}

?>
