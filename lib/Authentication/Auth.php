<?php

namespace Lib\Authentication;

use App\Models\User;

class Auth
{
    public static function login(User $user): void
    {
        $_SESSION['user'] = [
            'id' => $user->id,
            'role' => $user->role,
        ];
    }

    public static function user(): ?User
    {
        if (isset($_SESSION['user']['id'])) {
            $id = $_SESSION['user']['id'];
            return User::findById($id);
        }

        return null;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']['id']) && self::user() !== null;
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user && $user->role === 'admin';
    }

    public static function attempt(string $email, string $password): bool
    {
        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user->password)) {
            self::login($user);
            return true;
        }

        self::logout();
        return false;
    }
}
