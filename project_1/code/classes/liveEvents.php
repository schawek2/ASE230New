<?php
    //RestAPI/api/models/Student.php
    class LiveEvents {
        private $id;
        private $venue;
        private $artist;
        private $day;

        public function toArray() {
            return [
             'id' => $this->id,
             'venie' => $this->venue,
             'artist' => $this->artist,
             'day' => $this->day,
            ];
        }

        public function getId() {
            return $this->id;
        }

        public function getVenue(){
            return $this->venue;
        }

        public function setVenue(){
            $this->venue = trim($venue);
        }

         public function getArtist() {
            return $this->artist;
        }

        public function setArtist($artist) {
            $this->artist = trim($artist);
        }

        public function getDay() {
            return $this->day;
        }

        public function setDay($day) {
            $this->day = trim($day);
        }

    }

?>