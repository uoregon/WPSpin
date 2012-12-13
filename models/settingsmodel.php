<?php namespace WPSpin;
/**
 * Setup function. Creates required table if not found.
 * 
 * @global type $wpdb
 */

require_once dirname(__FILE__) . '/../config.php';


class SettingsModel extends ModelAbstract implements DBAccessInterface 
{
  public static function adminInit() {
    self::dbInit();
  }

  public static function dbInit() {
    global $wpdb;
    global $table_prefix;

    $sql = sprintf('CREATE TABLE IF NOT EXISTS `%s` (
      `showID` int PRIMARY KEY,
      `showName` VARCHAR(64) NOT NULL,
      `description` TEXT,
      `image` VARCHAR(64),
      `twitter` VARCHAR(32),
      `facebook` VARCHAR(128),
      `website` VARCHAR(128),
      `active` BOOLEAN NOT NULL);', $wpdb->prefix . $table_prefix . '_settings');

    $query = $wpdb->prepare($sql);
    $wpdb->query($query);
  }

}

?>
