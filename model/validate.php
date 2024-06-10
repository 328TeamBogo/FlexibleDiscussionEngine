<?php

class Validate {
    /**
     * Validate username
     */
    public static function validUsername($username) {
        // Username must be at least 3 characters long and contain only letters
        return preg_match('/^[a-zA-Z]{3,}$/', $username);
    }

    /**
     * Validate password
     */
    public static function validPassword($password) {
        // Password must be at least 6 characters long and contain only letters
        return preg_match('/^[a-zA-Z]{6,}$/', $password);
    }
}
