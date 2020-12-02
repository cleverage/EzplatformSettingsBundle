<?php

namespace Masev\SettingsBundle\Dal;


interface ParametersStorageInterface {

    public function has($key, $scope = 'default');

    public function get($key, $scope = 'default');

    public function getAll($key);

    public function set($key, $value, $user, $scope = 'default');

    public function remove($key, $user, $scope = 'default');
}