<?php

class Artists {
    private $id;
    private $name;
    private $agency;
    private $genre;

    public function toArray(){
        return [
             'id' => $this->id,
             'name' => $this->name,
             'agency' => $this->agency,
             'genre' => $this->genre,
            ];
    }

    public function getId() {
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = trim($name);
    }

    public function getAgency() {
        return $this->agency;
    }

    public function setAgency($agency) {
        $this->agency = trim($agency);
    }

    public function getGenre(){
        return $this->genre;
    }

    public function setGenre($genre){
        $this->genre = trim($genre);
    }
}

?>