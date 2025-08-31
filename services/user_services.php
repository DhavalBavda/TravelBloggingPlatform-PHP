<?php
require_once __DIR__ . '/../models/user_model.php';

class UserService {
    private $userModel;

    public function __construct($conn) {
        $this->userModel = new UserModel($conn);
    }

    // Create user
    public function createUser($username, $email, $phone_number, $password, $image = '') {
        return $this->userModel->insert_user($username, $email, $phone_number, $password, $image);
    }

    // Get user by ID
    public function getUserById($id) {
        return $this->userModel->get_user($id);
    }

    // Update user with optional profile image
    public function updateUser($id, $username, $phone_number, $image = null) {
        return $this->userModel->update_user($id, $username, $phone_number, $image);
    }

    public function registerUser($username, $email, $phone, $password) {
        return $this->userModel->insert_user($username, $email, $phone, $password);
    }

    public function loginUser($email, $password) {
        return $this->userModel->login_user($email, $password);
    }

}
?>
