<?php
require_once __DIR__."/../config/dbconfig.php";
require_once __DIR__."/../services/user_services.php";
require_once __DIR__."/../services/blog_services.php";
require_once __DIR__."/../services/comment_services.php";


// ✅ Always start the session before using $_SESSION
session_start();

$page   = $_GET['page']   ?? null;
$action = $_GET['action'] ?? null;
$id     = $_GET['id']     ?? null;

$userService = new UserService($conn);
$blogService=new BlogService($conn);
$commentService=new CommentService($conn);


switch($page){
    case 'users':
        
        if ($action === null) { //http://localhost/Travel_Blogging_Platform/public/index.php?page=users&id=9d062656-d275-4b67-950c-8185cad5f88f
            $userid = $_SESSION['userid'];

            if($userid){
                $user = $userService->getUserById($userid);
                $blogs = $blogService->getBlogsByUserId($userid);
                $comments = $commentService->getCommentsByUser($userid);
                include __DIR__ . '/views/user_profile.php';
            }
            else{
                include __DIR__ . '/views/login.php';
            }
        }
        break;

    case 'auth':
        if ($action === 'login') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $_POST['email'];
                $password = $_POST['password'];
                $user = $userService->loginUser($email, $password);

                if ($user) {
                    $_SESSION['userid'] = $user['id'];
                   
                    header("Location: index.php?page=users&id=" . $user['id']);
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
            session_destroy();
            header("Location: index.php?page=home&action=get");
            exit;
        }
        break;



    case 'home':
        if ($action === 'get'){
            // http://localhost/Travel_Blogging_Platform/public/index.php?page=home&action=get
            include __DIR__.'/views/landing.php';
        }
        break;

    case 'blog':
        if ($action === 'create'){ // http://localhost/Travel_Blogging_Platform/public/index.php?page=blog&action=create
            
            if(isset($_SESSION['userid'])){
                include __DIR__ . '/views/blog_form.php';
            }
            else{
                include __DIR__ . '/views/login.php';
            }
        }
        elseif ($action === 'get'){ // http://localhost/Travel_Blogging_Platform/public/index.php?page=blog&action=get&pageNo=1

            include __DIR__ . '/views/blog_listing.php';
        }
        elseif ($action === 'getbyid'){ // http://localhost/Travel_Blogging_Platform/public/index.php?page=blog&action=getbyid&id="id of the blog"

            include __DIR__.'/views/read_blog.php';
        }
        break;

    default:
        echo "Page not found!";
}
?>
