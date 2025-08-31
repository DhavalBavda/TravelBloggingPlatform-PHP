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
            $userid = $id ?? null;

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
    case 'auth':
        if ($action === 'login') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $_POST['email'];
                $password = $_POST['password'];
                $user = $userService->loginUser($email, $password);

                if ($user) {
                    session_start();
                    $_SESSION['user'] = $user;
                   
                    header("Location: index.php?page=users&action=profile&id=" . $user['id']);
                    exit;
                } else {
                    echo "Invalid login!";
                }
            } else {
                include __DIR__ . '/views/login.php';
            }
        } elseif ($action === 'register') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $result = $userService->registerUser(
                    $_POST['username'],
                    $_POST['email'],
                    $_POST['phone'],
                    $_POST['password']
                );
                if (strpos($result, 'successfully') !== false) {
                    header("Location: index.php?page=auth&action=login");
                    exit;
                } else {
            echo $result;
        }
            } else {
                include __DIR__ . '/views/register.php';
            }
        } elseif ($action === 'logout') {
            session_start();
            session_destroy();
            echo "Logged out successfully!";
        }
        break;



    default:
        echo "Page not found!";
}
?>
