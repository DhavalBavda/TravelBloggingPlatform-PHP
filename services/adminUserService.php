<?php
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/userService.php';

class AdminUserService extends UserService{

    public function __construct($conn) {
        parent::__construct($conn);
        echo "ADMIN USER SERVICE Called";
    }

    public function deleteUser($id){
        return $this->userModel->delete_user($id);
    }

    // public function promoteToAdmin($userId){
    //     return $this->
    // }
}
?>