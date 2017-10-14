<?php
    // CRUD interface of each model

    interface DataModel{
        public function setModel($data);
        public function create($data);
        public function read($id);
        public function update($id, $data);
        public function delete($id);
    }
?>