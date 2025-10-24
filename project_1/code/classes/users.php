<?php
    // basic class user 
    //RestAPI/api/models/Student.php
    class User {
        private $id;
        private $name;
        private $email;
        private $password;

        public function toArray() {
            return [
             'id' => $this->id,
             'name' => $this->name,
             'email' => $this->email,
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

        public function getEmail() {
            return $this->email;
        }

        public function setEmail($email) {
            $this->email = trim($email);
        }

        public function setPassHash($password){
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        }

        public function getPassHash(){
            return $this->password;
        }

    }
?>
