<?php
    //RestAPI/api/models/Student.php
    class Albums {
        private $id;
        private $albumName;
        private $artistName;
        private $numOfSongs;

        public function toArray() {
            return [
             'id' => $this->id,
             'albumName' => $this->albumName,
             'artistName' => $this->artistName,
             'numOfSongs' => $this->numOfSongs,
            ];
        }

        public function getId() {
            return $this->id;
        }

        public function getName(){
            return $this->albumName;
        }

        public function setName($albumName){
            $this->albumName = trim($albumName);
        }

        public function getArtistName(){
            return $this->getArtistName;
        }

        public function setArtistName($artistName){
            $this->artistName = trim($artistName);
        }

        public function getNumOfSongs(){
            return $this->$numOfSongs;
        }

        public function setNumOfSongs($numOfSongs){
            $this->numOfSongs = (int)$numOfSongs;
        }

    }

?>