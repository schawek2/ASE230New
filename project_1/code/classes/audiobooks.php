<?php
    //RestAPI/api/models/Student.php
    class Audiobooks {
        private $id;
        private $bookName;
        private $writerName;
        private $readerName;

        public function toArray() {
            return [
             'id' => $this->id,
             'bookName' => $this->bookName,
             'writerName' => $this->writerName,
             'readerName' => $this->readerName,
            ];
        }

        public function getId() {
            return $this->id;
        }

        public function getBookName(){
            return $this->bookName;
        }

        public function setBookName($bookName){
            $this->bookName = trim($bookName);
        }

        public function getWriterName() {
            return $this->writerName;
        }

        public function setWriterName($writerName) {
            $this->writerName = trim($writerName);
        }

        public function getReaderName() {
            return $this->readerName;
        }

        public function setReaderName($readerName) {
            $this->readerName = trim($readerName);
        }
    }

?>