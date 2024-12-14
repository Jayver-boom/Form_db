<?php
class Patch {

    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Update user details
    public function patchUsers($body, $id) {
        $values = [];
        $errmsg = "";
        $code = 0;

        foreach ($body as $value) {
            array_push($values, $value);
        }

        array_push($values, $id);

        try {
            $sqlString = "UPDATE users_tbl SET username=?, password=? WHERE id = ?";
            $sql = $this->pdo->prepare($sqlString);
            $sql->execute($values);

            $code = 200;
            $data = null;

            return array("data" => $data, "code" => $code);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }

        return array("errmsg" => $errmsg, "code" => $code);
    }

    public function patchCategory($body, $id) {
        $values = [];
        $errmsg = "";
        $code = 0;

        foreach ($body as $value) {
            array_push($values, $value);
        }

        array_push($values, $id);

        try {
            $sqlString = "UPDATE categories_tbl SET name=?, description=? WHERE id = ?";
            $sql = $this->pdo->prepare($sqlString);
            $sql->execute($values);

            $code = 200;
            $data = null;

            return array("data" => $data, "code" => $code);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }

        return array("errmsg" => $errmsg, "code" => $code);
    }

    // Archive users
    public function archiveUsers($id) {
        $errmsg = "";
        $code = 0;

        try {
            $sqlString = "UPDATE users_tbl SET isdeleted=1 WHERE id = ?";
            $sql = $this->pdo->prepare($sqlString);
            $sql->execute([$id]);

            $code = 200;
            $data = null;

            return array("data" => $data, "code" => $code);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }

        return array("errmsg" => $errmsg, "code" => $code);
    }

    

    
}
?>