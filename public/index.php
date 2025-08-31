<?php
require_once __DIR__."/../config/dbconfig.php";
require_once __DIR__."/../services/user_services.php";

$page   = $_GET['page']   ?? 'users';
$action = $_GET['action'] ?? 'profile';
$id     = $_GET['id']     ?? null;

$userService = new UserService($conn); 

switch($page){
    case 'users':
        if ($action === 'create'){
            echo $userService->createUser("Testing User","test@example.com","999877776","habibi");

        } elseif ($action === 'profile') {
            $userid = "5f055894-643c-4ec2-9267-da5e1208413b";

            $user = $userService->getUserById($userid);

            if ($user) {
                include __DIR__ . '/views/user_profile.php';
            } else {
                echo "User not found!";
            }

        } elseif ($action === 'update') {
            echo $userService->updateUser($id, "UpdatedName", "1234567890");

        } else {
            echo "Invalid action!";
        }
        break;

    default:
        echo "Page not found!";
}
?>
