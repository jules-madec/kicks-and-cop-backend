<?php

namespace Models;

use Exception;
use PDO;

class Favorites extends Database
{
    private $user;
    private $sneaker;

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($value)
    {
        if (empty($value)) throw new Exception('User is required');
        if (!filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) throw new Exception('Invalid user id');

        $this->user = (int) $value;
    }

    public function getSneaker()
    {
        return $this->sneaker;
    }

    public function setSneaker($value)
    {
        if (empty($value)) throw new Exception('Sneaker is required');
        if (!filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) throw new Exception('Invalid sneaker id');

        $this->sneaker = (int) $value;
    }


    public function addFavorite()
    {
        $queryExecute = $this->db->prepare("INSERT IGNORE INTO `favorites`(`user`, `sneaker`) 
            VALUES (:user, :sneaker)");

        $queryExecute->bindValue(':user', $this->user, PDO::PARAM_INT);
        $queryExecute->bindValue(':sneaker', $this->sneaker, PDO::PARAM_INT);

        return $queryExecute->execute();
    }


    public function removeFavorite()
    {
        $queryExecute = $this->db->prepare("DELETE FROM `favorites` 
            WHERE `user` = :user AND `sneaker` = :sneaker");

        $queryExecute->bindValue(':user', $this->user, PDO::PARAM_INT);
        $queryExecute->bindValue(':sneaker', $this->sneaker, PDO::PARAM_INT);

        return $queryExecute->execute();
    }


    public function isFavorite()
    {
        $queryExecute = $this->db->prepare("SELECT COUNT(*) FROM `favorites` 
            WHERE `user` = :user AND `sneaker` = :sneaker");

        $queryExecute->bindValue(':user', $this->user, PDO::PARAM_INT);
        $queryExecute->bindValue(':sneaker', $this->sneaker, PDO::PARAM_INT);
        $queryExecute->execute();

        return $queryExecute->fetchColumn() > 0;
    }


    public function toggleFavorite()
    {
        if ($this->isFavorite()) {
            $this->removeFavorite();
            return false;
        }
        $this->addFavorite();
        return true;
    }


    public function getFavoritesByUser()
    {
        $queryExecute = $this->db->prepare("SELECT s.* 
            FROM `favorites` f
            INNER JOIN `sneakers` s ON s.`id` = f.`sneaker`
            WHERE f.`user` = :user");

        $queryExecute->bindValue(':user', $this->user, PDO::PARAM_INT);
        $queryExecute->execute();

        return $queryExecute->fetchAll(PDO::FETCH_ASSOC);
    }
}
