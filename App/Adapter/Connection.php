<?php

namespace App\Adapter;

use App\Adapter\Connection\SimplePDO;
use App\Config;

class Connection {
  public function __invoke(Config $config) {
    $config = $config->getConfig();
    $adapter = $config['adapter'];

    return new SimplePDO($adapter['host'], $adapter['database'], $adapter['user'], $adapter['password']);
  }
}
