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
    public function deleteCategory($categoryId) {
        $code = 0;
        $payload = null;
        $remarks = "";
        $message = "";
    
        try {
            $adminHeaders = getallheaders();
            $adminUsername = $adminHeaders['X-Auth-User'];
    
            // Verify if the requesting user is an admin
            $sqlCheckAdmin = "SELECT role FROM users_tbl WHERE username=?";
            $stmtAdmin = $this->pdo->prepare($sqlCheckAdmin);
            $stmtAdmin->execute([$adminUsername]);
    
            if ($stmtAdmin->rowCount() > 0) {
                $adminResult = $stmtAdmin->fetch();
                if ($adminResult['role'] < 2) { // Admin role required
                    $code = 403;
                    $remarks = "failed";
                    $message = "Unauthorized. Admin access required.";
                    return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
                }
            } else {
                $code = 403;
                $remarks = "failed";
                $message = "Admin username not found.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }
    
            // Check if the category exists
            $sqlCheckCategory = "SELECT id FROM categories_tbl WHERE id=?";
            $stmtCategory = $this->pdo->prepare($sqlCheckCategory);
            $stmtCategory->execute([$categoryId]);
    
            if ($stmtCategory->rowCount() == 0) { // Category does not exist
                $code = 401;
                $remarks = "failed";
                $message = "Category does not exist.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }
    
            // Delete the category
            $sqlDelete = "DELETE FROM categories_tbl WHERE id=?";
            $stmtDelete = $this->pdo->prepare($sqlDelete);
            $stmtDelete->execute([$categoryId]);
    
            $code = 200;
            $remarks = "success";
            $message = "Category deleted successfully.";
            $payload = array("deleted_category_id" => $categoryId);
    
        } catch (\PDOException $e) {
            $code = 400;
            $remarks = "failed";
            $message = $e->getMessage();
        }
    
        return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
    }
    

    // Update user role
    public function updateRole($body) {
        $code = 0;
        $payload = null;
        $remarks = "";
        $message = "";
    
        try {
            $adminHeaders = getallheaders();
            $adminUsername = $adminHeaders['X-Auth-User'];
    
            // Verify if the requesting user is an admin
            $sqlCheckAdmin = "SELECT role FROM users_tbl WHERE username=?";
            $stmtAdmin = $this->pdo->prepare($sqlCheckAdmin);
            $stmtAdmin->execute([$adminUsername]);
    
            if ($stmtAdmin->rowCount() > 0) {
                $adminResult = $stmtAdmin->fetch();
                if ($adminResult['role'] < 2) { // Admin role required
                    $code = 403;
                    $remarks = "failed";
                    $message = "Unauthorized. Admin access required.";
                    return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
                }
            } else {
                $code = 403;
                $remarks = "failed";
                $message = "Admin username not found.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }
    
            // Check if the target username exists
            $sqlCheckUser = "SELECT username FROM users_tbl WHERE username=?";
            $stmtUser = $this->pdo->prepare($sqlCheckUser);
            $stmtUser->execute([$body->username]);
    
            if ($stmtUser->rowCount() == 0) { // Username does not exist
                $code = 401;
                $remarks = "failed";
                $message = "Username does not exist.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }
    
            // Update the role of the target user
            $sqlString = "UPDATE users_tbl SET role=? WHERE username=?";
            $stmtUpdate = $this->pdo->prepare($sqlString);
            $stmtUpdate->execute([$body->role, $body->username]);
    
            $code = 200;
            $remarks = "success";
            $message = "User role updated successfully.";
            $payload = array("username" => $body->username, "new_role" => $body->role);
    
        } catch (\PDOException $e) {
            $code = 400;
            $remarks = "failed";
            $message = $e->getMessage();
        }
    
        return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
    }
    
}
?>
