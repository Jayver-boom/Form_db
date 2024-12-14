<?php 
class Delete {

    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function DeleteShows($id) {
        $errmsg = "";
        $code = 0;

        try {
            // Update the isdeleted field to 1 instead of deleting the record
            $sqlString = "UPDATE users_tbl SET isdeleted = 1 WHERE id = ?";
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