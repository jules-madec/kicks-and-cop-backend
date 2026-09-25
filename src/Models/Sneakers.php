<?php

namespace Models;

use Exception;
use PDO;

class Sneakers extends Database
{
    private $id;
    private $sneaker_name;
    private $description;
    private $image;
    private $price;

    public function getById($id)
    {
        $queryExecute = $this->db->prepare("SELECT * FROM `sneakers` WHERE id = :id");
        $queryExecute->bindValue(':id', $this->$id, PDO::PARAM_INT);
        $queryExecute->execute();

        return $queryExecute->fetch(PDO::FETCH_ASSOC);
    }

    public function getSneaker_name()
    {
        return $this->sneaker_name;
    }

    public function setSneaker_name($value)
    {
        if (empty($value)) throw new Exception('Sneaker name is required');
        $this->sneaker_name = $value;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        if (empty($value)) throw new Exception('Description is required');
        $this->description = $value;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($value)
    {
        if (empty($value)) throw new Exception('Image is required');
        $this->image = $value;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($value)
    {
        if (!is_numeric($value) || $value < 0) throw new Exception('Invalid price');
        $this->price = $value;
    }

    public function getAllSneakers()
    {
        $query = $this->db->query("SELECT * FROM `sneakers`");

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = $this->db->prepare("SELECT * FROM `sneakers` WHERE `id` = :id");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
