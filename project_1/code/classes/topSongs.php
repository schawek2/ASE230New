<?php
    // basic class user 
    //RestAPI/api/models/Student.php
    class TopSongs {
        private $id;
        private $name;
        private $artist;
        private $rank;

        public function toArray() {
            return [
             'id' => $this->id,
             'name' => $this->name,
             'artist' => $this->artist,
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

        public function getArtist() {
            return $this->artist;
        }

        public function setArtist($artist) {
            $this->artist = trim($artist);
        }

        public function getRank() {
            return $this->rank;
        }

        public function setRank($rank) {
            $this->rank = (int)$rank;
        }

    }
?>
