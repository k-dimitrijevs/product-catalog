<?php

namespace App\Repositories\UsersRepository;

use App\Models\User;
use App\MySQLConfig;
use PDO;

class MysqlUsersRepository extends MySQLConfig implements UsersRepository
{
    public function register(User $user): void
    {
        $sql = "INSERT INTO users (id, email, username, password) VALUES (:id, :email, :username, :password)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([
            ':id' => $user->getId(),
            ':email' => $user->getEmail(),
            ':username' => $user->getUsername(),
            ':password' => $user->getPassword()
        ]);
    }

    public function getByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($user)) return null;

        return new User(
            $user['id'],
            $user['email'],
            $user['username'],
            $user['password'],
        );
    }
}
