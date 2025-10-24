<?php
// basic class Playlists that can be used for future potentially
    class Playlists {
        private $id;
        private $usersName;
        private $playlistName;
        private $genre;

        
        public function toArray() {
            return [
                'id' => $this->id,
                'usersName' => $this->usersName,
                'name' => $this->playlistName,
                'genre' => $this->genre,
            ];
        }

        public function getPlaylistId() {
            return $this->id;
        }

        public function getUsersName() {
            return $this->userName;
        }

        public function setUsersName($usersName) {
            $this->usersname = trim($usersName);
        }

        public function playlistName() {
            return $this->playlistName;
        }

        public function setPlaylistName($playlistName) {
            $this->playlistName = trim($playlistName);
        }

        public function getGenre() {
            return $this->genre;
        }

        public function setGenre($genre) {
            $this->genre = trim($genre);
        }

    }
?>