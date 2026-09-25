<?php

namespace Models;

use Exception;
use PDO;

class Sizes extends Database
{
    private $id;
    private $size;
    private $sneaker;

    public function getId()
    {
        return $this->id;
    }
    public function setId($value)
    {
        $this->id = $value;
    }


    public function getSize()
    {
        return $this->size;
    }

    public function setSize($value)
    {
        if ($value === '' || $value === null) throw new Exception('Size is required');
        if (!filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 16, 'max_range' => 55]])) throw new Exception('Size must be between 16 and 55');

        $this->size = (int) $value;
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

    public function getSizesBySneaker()
    {
        $queryExecute = $this->db->prepare("SELECT `id`, `size` FROM `sizes` 
            WHERE `sneaker` = :sneaker 
            ORDER BY `size` ASC");

        $queryExecute->bindValue(':sneaker', $this->sneaker, PDO::PARAM_INT);
        $queryExecute->execute();

        return $queryExecute->fetchAll(PDO::FETCH_ASSOC);
    }
}
