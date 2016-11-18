<?php

namespace App;

use Phalcon\Config;
use Phalcon\DiInterface;
use PhalconApi\Api;

interface BootstrapInterface {

    public function run(Api $api, DiInterface $di, Config $config);

}