<?php
    // basic class user 
    //RestAPI/api/models/Student.php
    class TopArtist {
        private $id;
        private $name;
        private $genre;
        private $rank;

        public function toArray() {
            return [
             'id' => $this->id,
             'name' => $this->name,
             'genre' => $this->genre,
             'rank' => $this->rank,
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

        public function getGenre() {
            return $this->genre;
        }

        public function setGenre($genre) {
            $this->genre = trim($genre);
        }

        public function getRank() {
            return $this->rank;
        }

        public function setRank($rank) {
            $this->rank = (int)$rank;
        }

    }
?>
