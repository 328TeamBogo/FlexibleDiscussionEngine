<?php

class Validate {
    /**
     * Validate username
     */
    public static function validUsername($username)
    {
        // Username must be at least 3 characters long and contain only letters
        return preg_match('/^[a-zA-Z]{3,}$/', $username);
    }

    /**
     * Validate password
     */
    public static function validPassword($password) {
        // Password contains letters, numbers and special characters, at least 6 characters
        return preg_match('/^[a-zA-Z0-9!@#$%^&*()_+]{6,}$/', $password);
    }

    /**
     * Check if username already exists in the database
     */
    public static function usernameExists($username, $dbh)
    {
        $sql = "SELECT COUNT(*) FROM users WHERE username = :username";
        $statement = $dbh->prepare($sql);
        $statement->bindParam(':username', $username);
        $statement->execute();
        return $statement->fetchColumn() > 0;
    }
}
