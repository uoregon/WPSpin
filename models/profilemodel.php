<?php namespace WPSpin;
/**
 * Setup function. Creates required table if not found.
 * 
 * @global type $wpdb
 */

class ProfileModel extends ModelAbstract implements DBAccessInterface 
{
  private static $table_prefix = 'wpspin';

  public static function adminInit() {
    self::dbInit();
  }

  public static function dbInit() {
    global $wpdb;

    $sql = sprintf('CREATE TABLE IF NOT EXISTS `%s` (
      `showID` int PRIMARY KEY,
      `showName` VARCHAR(64) NOT NULL,
      `description` TEXT,
      `image` VARCHAR(64),
      `twitter` VARCHAR(32),
      `facebook` VARCHAR(128),
      `website` VARCHAR(128),
      `active` BOOLEAN NOT NULL);', $wpdb->prefix . self::$table_prefix . '_profile');

    $query = $wpdb->prepare($sql);
    $wpdb->query($query);
  }

}

?>
