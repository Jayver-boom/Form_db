<?php
Class Common{

    protected function logger ($user, $method, $action){
        //date and time, user, method, message -> text file .log
        $filename = date("Y-m-d") . ".log";
        $datetime = date("Y-m-d H:i:s");
        $logmessage = "$datetime, $method, $user, $action" . PHP_EOL;
        file_put_contents("./logs/$filename", $logmessage, FILE_APPEND | LOCK_EX);
    }

    private function generateInsertString($tableName, $body){
        $keys = array_keys($body);
        $fields = implode(",", $keys);
        $parameter_array = [];
        for($i = 0; $i < count($keys); $i++){
            $parameter_array[$i] = "?";
        }
        $parameters = implode(',', $parameter_array);
        $sql = "INSERT INTO $tableName($fields) VALUES ($parameters)";
        return $sql;
    }
    protected function getDataByTable($tableName, $condition, \PDO $pdo){
        $sqlString = "SELECT * FROM $tableName WHERE $condition";
        
        $data = array();
        $errmsg = "";
        $code = 0;

        try{
            if($result = $pdo->query($sqlString)->fetchAll()){
                foreach($result as $record){
                    array_push($data, $record);
                }
                $result = null;
                $code = 200;
                return array("code" => $code, "data" => $data);
            }
            else{
                $errmsg = "No data found";
                $code = 404;
            }
        }
        catch(\PDOException $e){
            $errmsg = $e -> getMessage();
            $code = 403;
        }

        return array("code" => $code, "errmsg" => $errmsg);
    }

    protected function getDataBySQL($sqlString, $condition, \PDO $pdo){     
        $data = array();
        $errmsg = "";
        $code = 0;

        try{
            if($result = $pdo->query($sqlString)->fetchAll()){
                foreach($result as $record){
                    array_push($data, $record);
                }
                $result = null;
                $code = 200;
                return array("code" => $code, "data" => $data);
            }
            else{
                $errmsg = "No data found";
                $code = 404;
            }
        }
        catch(\PDOException $e){
            $errmsg = $e -> getMessage();
            $code = 403;
        }

        return array("code" => $code, "errmsg" => $errmsg);
    }

    protected function generateResponse($data, $remark, $message, $statusCode){
        $status = array(
            "remark" => $remark,
            "message" => $message
        );

        http_response_code($statusCode);

        return array(
            "payload" => $data,
            "status" => $status,
            "prepared_by" => "Lawrence",
            "date_generated" => date_create()
        );
    }

    public function postData($tableName, $body, \PDO $pdo){
        $values = [];
        $errmsg = "";
        $code = 0;

        foreach($body as $value){
            array_push($values, $value);
        }
        print_r($body);
        try{
            $sqlString = $this->generateInsertString($tableName, json_decode(json_encode($body)), true);
            $sql = $pdo->prepare($sqlString);
            $sql->execute($values);

            $code = 200;
            $data = null;

            return array("data"=>$data, "code"=>$code); 
        }
        catch(\PDOException $e){
            $errmsg = $e->getMessage();
            $code = 400;
        }

        
        return array("errmsg"=>$errmsg, "code"=>$code); 
    }
    }


?>