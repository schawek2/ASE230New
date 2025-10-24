<?php
    //RestAPI/api/models/Student.php
    class Podcasts {
        private $id;
        private $name;
        private $hostName;
        private $totalTime;

        public function toArray() {
            return [
             'id' => $this->id,
             'name' => $this->name,
             'hostName' => $this->hostName,
             'totalTime' => $this->totalTime,
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

        public function getHostName() {
            return $this->hostName;
        }

        public function setHostName($hostName) {
            $this->hostName = trim($hostName);
        }

        public function getTotalTime() {
            return $this->totalTime;
        }

        public function setTotalTime($totalTime) {
            $this->totalTime = (int)$totalTime;
        }

    }
?>
