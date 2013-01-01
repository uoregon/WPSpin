<?php namespace WPSpin;

abstract class ModelAbstract {

  protected static function getMetaData($id, $key)
  {
    return get_post_meta($id, $key, true);
  }

}


?>
