<?php

namespace App;

class Config {
  protected $config;

  public function __construct() {
    $configFiles = glob('App/Config/*.php*');
    $configs = [];

    if (count($configFiles)) {
      foreach ($configFiles as $configFile) {
        if (is_readable($configFile)) {
          $configs = array_merge_recursive(include $configFile, $configs);
        }
      }
    }

    $this->config = $configs;
  }

  public function getConfig() {
    return $this->config;
  }
}
