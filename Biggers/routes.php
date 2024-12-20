<?php

//import get and post files
require_once "./config/database.php";
require_once "./modules/Get.php";
require_once "./modules/Post.php";
require_once "./modules/Patch.php";
require_once "./modules/Delete.php";
require_once "./modules/Auth.php";
require_once "./modules/Crypt.php";

$db = new Connection();
$pdo = $db->connect();

//instantiate post, get class
$post = new Post($pdo);
$patch = new Patch($pdo);
$get = new Get($pdo);
$delete = new Delete($pdo);
$auth = new Authentication($pdo);
$crypt = new Crypt();

//retrieved and endpoints and split
if(isset($_REQUEST['request'])){
    $request = explode("/", $_REQUEST['request']);
}
else{
    echo "URL does not exist.";
}

//get post put patch delete etc
//Request method - http request methods you will be using

switch($_SERVER['REQUEST_METHOD']){

    case "GET":
        if($auth->isAuthorized()){
            $headers = getallheaders();
            switch($request[0]){

            case "shows":
                $dataString = json_encode($get->getShows($request[1] ?? null));
                echo $crypt->encryptData($dataString);
            break;

            case "channel":
                $dataString = json_encode($get->getChannel($request[1] ?? null));
                echo $crypt->encryptData($body);
            break;

            case "log";
                echo json_encode($get->getLogs($request[1] ?? date("Y-m-d")));
            break;

            case "allusers": // Example: Admins only
                $requiredRole = 2; // Assuming 2 = Admin
                if ($auth->isAuthorized($requiredRole)) {
                    echo json_encode($get->getAllUsers());
                } else {
                    http_response_code(403);
                    echo json_encode(["error" => "Access denied. Admins only."]);
                }
                break;

            case "allposts": // Moderators or Admins
                echo json_encode($get->getAllPosts());
            break;

            case "allcategories":
                echo json_encode($get->getAllCategories());
            break;

            case "allpostinacategory":
                // echo json_encode($get->getPostsByCategory());
                echo json_encode($get->getPostsByCategory($request[1]));
            break;

            case "allcommentsinapost":
                // echo json_encode($get->getPostsByCategory());
                echo json_encode($get->getCommentsByPost($request[1]));
            break;

            case "finduser":
                // Retrieve a specific user by username
                $username = $request[1] ?? null;
                if ($username) {
                    echo json_encode($get->findUser($username));
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "Username is required."]);
                }
                break;

            default:
                http_response_code(401);
                echo "This is invalid endpoint";
            break;
        }
    }
    else {
        echo "Unauthorized";
    }

    break;


    case "POST":
        $body = json_decode(file_get_contents("php://input"));
        switch($request[0]){
            case "login":
                echo json_encode($auth->login($body));
            break;
            
            case "user":
                echo json_encode($auth->addAccount($body));
            break;

            case "allposts":
                echo $crypt->decryptData($body);
            break;

            case "channel":
                echo json_encode($post->postChannel($body));
            break;

            default:
                http_response_code(401);
                echo "This is invalid endpoint";
            break;

            case "createcategory":
                echo json_encode($auth->addCategory($body));
            break;
        }
    break;


    case "PATCH":
        
        $body = json_decode(file_get_contents("php://input"));
        switch($request[0]){
            case "updateusers":
                echo json_encode($patch->patchUsers($body, $request[1]));
                break;

            case "updatecategory":
                echo json_encode($patch->patchCategory($body, $request[1]));
                break;
            
            case "updaterole":
                echo json_encode($patch->updateRole($body));
                break;
            
        }
    break;

    case "DELETE":

        $body = json_decode(file_get_contents("php://input"));
        switch ($request[0]) {
            case "deleteusers":
                echo json_encode($delete->deleteUsers($request[1]));
                break;

            case "shows":
                echo json_encode($patch->archiveShows($request[1]));
                break;

            default:
                http_response_code(401);
                echo "Invalid DELETE endpoint";
                break;

            case "deletecategories":
                echo json_encode($delete->deleteCategory($request[1]));
                break;

            case "archivecategories":
                echo json_encode($delete->archiveCategory($request[1]));
                break;

            case "archiveuser":
                echo json_encode($delete->archiveUsers($request[1]));
                break;

            case "deleteuser":
                echo json_encode($delete->deleteUser($request[1]));
                break;

            case "deletepost":
                echo json_encode($delete->deletePost($request[1]));
                break;
                
            case "deletecomment":
                echo json_encode($delete->deleteComment($request[1]));
                break;
                
            case "archpost":
                echo json_encode($delete->archivePost($request[1]));
                break;

            case "archivecomment":
                echo json_encode($delete->archiveComments($request[1]));
                break;
        

        }

    default:
        http_response_code(400);
        echo "Invalid Request Method.";
    break;
}



?>