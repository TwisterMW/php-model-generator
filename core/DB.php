<?php
    require 'db-config.php';

    class DB{
        var $tables;

        function __construct(){
            $conn = $this->databaseConnect();
            
            if(!$conn){
                return false;
            }else{
                $conn->close();
            }
        }

        function databaseConnect(){
            return mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        }

        function generateQuery($action, $affected_table, $column_data = NULL, $condition_key = NULL, $condition_value = NULL){
            switch(strtolower($action)){
                case 'create':
                    $query_half1 = "INSERT INTO " . $affected_table . " (";
                    $query_half2 = "VALUES (";

                    $column_names = array_keys($column_data);
                    $end = end($column_names);

                    foreach($column_data as $property => $value){
                        $query_half1 .= $property;
                        $query_half2 .= (gettype($value) === "integer") ? $value : "'" . $value . "'";

                        $query_half1 .= ($property !== $end) ? ", " : ") ";
                        $query_half2 .= ($property !== $end) ? ", " : ")";
                    }

                    $query = $query_half1 . $query_half2;
                break;
                case 'read':
                    $query = "SELECT * FROM " . $affected_table . " WHERE " . $condition_key . "=" . $condition_value;
                break;
                case 'update':
                    $query = "UPDATE " . $affected_table . " SET ";

                    $column_names = array_keys($column_data);
                    $end = end($column_names);

                    foreach($column_data as $property => $value){
                        $query .= $property . "=";
                        $query .= (gettype($value) === "integer") ? $value : "'" . $value . "'";

                        $query .= ($property !== $end) ? ", " : " ";
                    }

                    $query .= "WHERE " . $condition_key . "=";
                    $query .= (gettype($condition_value) === "integer") ? $condition_value : "'" . $condition_value . "'";
                break;
                case 'delete':
                    $query = "DELETE FROM " . $affected_table . " WHERE " . $condition_key . "=" . $condition_value;
                break;
            }

            return $query;
        }
    }
?>