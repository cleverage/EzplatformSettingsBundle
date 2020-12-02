<?php

namespace Masev\SettingsBundle\Dal;

class MysqlParametersStorage implements ParametersStorageInterface
{
    private $db;

    public function __construct($config)
    {
        $this->db = \Doctrine\DBAL\DriverManager::getConnection($config);
    }

    public function has($key, $scope = null)
    {
        if ($scope) {
            $statement = $this->db->prepare("SELECT count(id) FROM `masev_settings` WHERE `identifier`=:key AND `scope`=:scope");
            $statement->bindValue(":scope", $scope);
        } else {
            $statement = $this->db->prepare("SELECT count(id) FROM `masev_settings` WHERE `identifier`=:key");
        }

        $statement->bindValue(":key", $key);

        $statement->execute();
        $result = $statement->fetch();

        return $result['count(id)'] > 0 ? true : false;
    }

    public function get($key, $scope = 'default')
    {
        $statement = $this->db->prepare("SELECT * FROM `masev_settings` WHERE `identifier`=:key AND `scope`=:scope");
        $statement->bindValue(":key", $key);
        $statement->bindValue(":scope", $scope);

        $statement->execute();
        $result = $statement->fetch();

        return is_array($result) ? $result : false;
    }

    public function getAll($key)
    {
        $statement = $this->db->prepare("SELECT * FROM `masev_settings` WHERE `identifier`=:key");
        $statement->bindValue(":key", $key);

        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return count($result) > 0 ? $result : false;
    }

    public function set($key, $value, $user, $scope = 'default')
    {
        $currentDate = new \DateTime();
        $statement = $this->db->prepare("REPLACE INTO `masev_settings` (identifier, value, scope, updatedAt, updatedBy) VALUES (:identifier, :value, :scope, :updatedAt, :updatedBy)");
        $statement->bindValue(":identifier", $key);
        $statement->bindValue(":value", $value);
        $statement->bindValue(":scope", $scope);
        $statement->bindValue(":updatedAt", $currentDate->format('Y-m-d H:i:s'));
        $statement->bindValue(":updatedBy", $user);

        return $statement->execute();
    }

    public function remove($key, $user, $scope = 'default')
    {
        $currentDate = new \DateTime();

        $statement = $this->db->prepare("DELETE FROM `masev_settings` WHERE `identifier`=:identifier AND `scope`=:scope");
        $statement->bindValue(":identifier", $key);
        $statement->bindValue(":scope", $scope);
/*        $statement->bindValue(":updatedAt", $currentDate->format('Y-m-d H:i:s'));
        $statement->bindValue(":updatedBy", $user);*/

        return $statement->execute();
    }
}