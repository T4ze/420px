<?php

class User {
    private function __construct() {}

    static function registerUser($username, $password, $confirm) {
        try {
            if (strlen($username) <= 0)
                return "Username too small";
            if (strlen($password) <= 0)
                return "Password too small";

            // ERROR: Passwords doesn't match
            if ($password !== $confirm)
                return "Passwords doesn't match.";

            $c = SPDO::getInstance()->rowCount("SELECT id from `users` where `username`=:username", [
                "username" => strtolower($username)
            ]);

            // ERROR: Username already taken
            if ($c > 0) return "Username " . $username . " already taken.";

            // Register user
            $p = SPDO::getInstance()->prepare('INSERT INTO `users` (`username`, `password`) VALUES (LOWER(:username), :password)');
            $r = $p->execute([
                "username" => htmlspecialchars($username),
                "password" => crypt($password, strtolower($username)),
            ]);

            $lastId = SPDO::getInstance()->lastInsertId();
            if (!file_exists('public/' . $lastId)) {
                mkdir('public/' . $lastId, 0777, true);
            }

            $_SESSION['user']['id'] = $lastId;
            $_SESSION['user']['name'] = ucfirst(strtolower($username));
            header('Location: /profile.php');
            exit();
        } catch (PDOException $e) {
            echo 'Fail to register : ' . $e->getMessage();
            return "Impossible to register.";
        }
    }
 
    static function logUser($username, $password) {
        try {
            $r = SPDO::getInstance()->first("SELECT id from `users` WHERE `username`=:username AND `password`=:password", [
                "username" => htmlspecialchars($username),
                "password" => crypt($password, strtolower($username)),
            ]);

            if ($r) {
                $_SESSION['user']['id'] = $r['id'];
                $_SESSION['user']['name'] = ucfirst(strtolower($username));
                $_SESSION['images'] = [];
                header('Location: /profile.php');
                exit();
            }

            return "The combination username/password doesn't match.";
        } catch (PDOException $e) {
            //echo 'Fail to register : ' . $e->getMessage();
            return "Error during the log in";
        }
    }

    static function userById($id) {
        try {
            $r = SPDO::getInstance()->first("SELECT `username` from `users` WHERE `id`=:id", [
                "id" => htmlspecialchars($id),
            ]);

            if ($r) {
                return [
                    "username" => $r['username'],
                    "images" => Image::getImagesByUser($id),
                ];
            }

        } catch (PDOException $e) {
            //echo 'Fail to register : ' . $e->getMessage();
        }

        header('Location: /');
        exit();
    }
}

?>