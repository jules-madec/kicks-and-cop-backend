<?php

namespace Models;

use Exception;
use PDO;

class Cart extends Database
{
    private $user;
    private $sneaker;
    private $size;

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

    public function getSize()
    {
        return $this->size;
    }


    public function setSize($value)
    {
        if (empty($value)) throw new Exception('Size is required');
        if (!filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) throw new Exception('Invalid size id');

        $this->size = (int) $value;
    }


    public function register()
    {
        $queryExecute = $this->db->prepare("INSERT INTO `carts`(`user`,`sneaker`, `size`) 
			VALUES (:user,:sneaker,:size)");

        $queryExecute->bindValue(':user', $this->user, PDO::PARAM_STR);
        $queryExecute->bindValue(':sneaker', $this->sneaker, PDO::PARAM_STR);
        $queryExecute->bindValue(':size', $this->size, PDO::PARAM_STR);


        return $queryExecute->execute();
    }



    public function removeFromCart()
    {
        $queryExecute = $this->db->prepare("DELETE FROM `cart` 
            WHERE `user` = :user AND `sneaker` = :sneaker AND `size` = :size");

        $queryExecute->bindValue(':user', $this->user, PDO::PARAM_INT);
        $queryExecute->bindValue(':sneaker', $this->sneaker, PDO::PARAM_INT);
        $queryExecute->bindValue(':size', $this->size, PDO::PARAM_INT);

        return $queryExecute->execute();
    }

    public function getCartByUser()
    {
        $queryExecute = $this->db->prepare("SELECT cart.sneaker, cart.size, sneakers.sneaker_name, sneakers.image, sneakers.price, sizes.size AS pointure
        FROM cart
        JOIN sneakers ON sneakers.id = cart.sneaker
        JOIN sizes ON sizes.id = cart.size
        WHERE cart.user = :user");

        $queryExecute->bindValue(':user', $this->user, PDO::PARAM_INT);
        $queryExecute->execute();

        return $queryExecute->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotal()
    {
        $queryExecute = $this->db->prepare("SELECT SUM(sneakers.price) FROM cart
        JOIN sneakers ON sneakers.id = cart.sneaker
        WHERE cart.user = :user");

        $queryExecute->bindValue(':user', $this->user, PDO::PARAM_INT);
        $queryExecute->execute();

        return (int) $queryExecute->fetchColumn();
    }

    public function countItems()
    {
        $queryExecute = $this->db->prepare("SELECT COUNT(*) FROM `cart` WHERE `user` = :user");
        $queryExecute->bindValue(':user', $this->user, PDO::PARAM_INT);
        $queryExecute->execute();

        return (int) $queryExecute->fetchColumn();
    }

    public function clearCart()
    {
        $queryExecute = $this->db->prepare("DELETE FROM `cart` WHERE `user` = :user");

        $queryExecute->bindValue(':user', $this->user, PDO::PARAM_INT);

        return $queryExecute->execute();
    }
}
