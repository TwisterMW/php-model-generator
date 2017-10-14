<?php
    require '../../core/DB.php';
    require '../../core/DataModel.php';

    define("TABLE_NAME", '<<tableName>>');

    class <<modelName>> implements DataModel{
        var $dbo;
<<modelProperties>>
        function __construct($action, $params){
            $this->dbo = new DB();

            switch($action){
                case 'create':
                    $this->create($params['data']);
                break;
                case 'read':
                    $this->read($params['id']);
                break;
                case 'update':
                    $this->update($params['id'], $params['data']);
                break;
                case 'delete':
                    $this->delete($params['id']);
                break;
            }
        }

        public function setModel($data){
            foreach($data as $property => $value){
                $this->{$property} = $value;
            }
        }

        public function create($data){
            $conn = $this->dbo->databaseConnect();
            mysqli_set_charset($conn, "utf8");

            $query = $this->dbo->generateQuery("create", TABLE_NAME, $data);
            $res = $conn->query($query);

            $this->id = $conn->insert_id;

            $conn->close();
            $this->setModel($data);
        } 

        public function read($id){
            $conn = $this->dbo->databaseConnect();
            mysqli_set_charset($conn, "utf8");

            $query = $this->dbo->generateQuery("read", TABLE_NAME, NULL, "id", $id);

            $res = $conn->query($query);
            $data = $res->fetch_assoc();

            $conn->close();
            $this->setModel($data);
        }

        public function update($id, $data){
            $conn = $this->dbo->databaseConnect();
            mysqli_set_charset($conn, "utf8");

            $query = $this->dbo->generateQuery("update", TABLE_NAME, $data, "id", $id);
            $res = $conn->query($query);

            $this->updated = ($conn->affected_rows > 0) ? true : false;
            $conn->close();

            $this->id = $id;
            $this->setModel($data);
        }

        public function delete($id){
            $conn = $this->dbo->databaseConnect();

            $query = $this->dbo->generateQuery("delete", TABLE_NAME, NULL, "id", $id);
            $this->updated = ($conn->query($query)) ? true : false;

            $conn->close();
        }
    }

?>