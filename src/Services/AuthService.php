<?php
// src/Services/AuthService.php
class AuthService {
    public function login($username, $password) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            return true;
        }
        return false;
    }

    public function isAdminLoggedIn() {
        return isset($_SESSION['admin_id']);
    }

    public function logout() {
        unset($_SESSION['admin_id']);
        session_destroy();
    }
}
?>