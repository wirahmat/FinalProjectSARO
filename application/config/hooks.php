<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$hook['pre_system'] = function() {
    $dotenv = new Dotenv\Dotenv(APPPATH);
    $dotenv->load();
};