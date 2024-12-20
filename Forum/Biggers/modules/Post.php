<?php

include_once "Common.php";
class Post extends Common{

    protected $pdo;

    public function __construct(\PDO $pdo){
        $this -> pdo = $pdo;
    }
    public function postChannel($body){
        $result = $this->postData("cards_tbl", $body, $this->pdo);
        if($result['code'] == 200){
            $this->logger("Flag", "POST", "Created a new channel record.");
            return $this->generateResponse($result['data'], "success", "Successfully created new records.", $result['code']);
        }
        $this->logger("Flag", "POST", $result['errmsg']);
        return $this->generateResponse(null, "failed", $result['errmsg'], $result['code']);

    }

    public function postShows($body){
        $result = $this->postData("adult_swim_bumps", $body, $this->pdo);
        if($result['code'] == 200){
            $this->logger("Flag", "POST", "Created a new show record.");
            return $this->generateResponse($result['data'], "success", "Successfully created new records.", $result['code']);
        }
        $this->logger("Flag", "POST", $result['errmsg']);
        return $this->generateResponse(null, "failed", $result['errmsg'], $result['code']);    
    }
}

?>