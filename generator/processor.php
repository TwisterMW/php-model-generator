<?php
    require '../core/DB.php';

    class ModelGenerator{
        var $dbo;
        var $tables;

        function __construct(){
            $this->dbo = new DB();
            $this->tables = $this->getTables();

            foreach($this->tables as $table){
                $modelName = $table;
                $this->generateModel($modelName);
            }
        }

        function getTables(){
            $conn = $this->dbo->databaseConnect();
            $query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='" . DB_NAME . "'";
            $res = $conn->query($query);

            $tables = [];
            while($r = $res->fetch_assoc()){
                array_push($tables, $r['TABLE_NAME']);
            }

            $conn->close();
            return $tables;
        }

        function getColumnsByTable($tableName){
            $conn = $this->dbo->databaseConnect();
            $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $tableName . "' AND TABLE_SCHEMA='" . DB_NAME . "'";
            $res = $conn->query($query);

            $columns = [];
            while($r = $res->fetch_assoc()){
                array_push($columns, $r['COLUMN_NAME']);
            }

            $conn->close();
            return $columns;
        }

        function generateModel($tableName){
            $rawProps = $this->getColumnsByTable($tableName);
            $modelProperties = "";

            foreach($rawProps as $prop){
                $modelProperties .= "\t\tvar $" . $prop . ";\n";
            }

            $modelName = str_replace("_", "", ucwords($tableName, "_"));

            $arraySearch = array("<<tableName>>", "<<modelName>>", "<<modelProperties>>");
            $arrayReplace = array($tableName, $modelName, $modelProperties);
            $modelTemplate = str_replace($arraySearch, $arrayReplace, file_get_contents('modelTemplate.tpl'));

            if(!is_dir("../models")) mkdir('../models');
            $modelPath = "../models/" . $modelName;

            if(!is_dir($modelPath)) mkdir($modelPath);
            file_put_contents($modelPath . "/" . $modelName . ".php", $modelTemplate);
        }
    }

    $mg = new ModelGenerator();
?>