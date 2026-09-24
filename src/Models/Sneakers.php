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

        $this->sneaker_name = htmlspecialchars($value);
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function setImage($value)
    {


        $this->image = $value;
    }

    public function getImage()
    {
        return $this->image;
    }
    public function setprice($value)
    {


        $this->price = $value;
    }

    public function getprice()
    {
        return $this->price;
    }

    public function getAllSneakers()
    {
        $queryExecute = $this->db->query("SELECT * FROM sneakers");

        return $queryExecute->fetchAll(PDO::FETCH_ASSOC);
    }
}
