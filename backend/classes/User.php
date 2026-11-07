<?php

declare(strict_types=1);

class User
{

    public static function findByLogin(string $login): ?array
    {
        $db = Database::getConnection();

        $stmt = $db->prepare(
            'SELECT * FROM users WHERE login = :login'
        );

        $stmt->execute(['login' => $login]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public static function create(array $data): int
    {
    
        $fullName = trim($data['full_name'] ?? '');


        if (!preg_match(
            '/^[А-Яа-яЁё]+(?:\s+[А-Яа-яЁё]+){2}$/u',
            $fullName
        )) {
            throw new InvalidArgumentException(
                'Введите фамилию, имя и отчество'
            );
        }


        $parts = preg_split('/\s+/u', $fullName);

        [$lastName, $firstName, $middleName] = $parts;

        $db = Database::getConnection();

        $passwordHash = password_hash(
            $data['password'],
            PASSWORD_DEFAULT
        );

        try {
            $db->beginTransaction();
            $stmt = $db->prepare(
                'INSERT INTO users
                (login, password_hash, phone, email, role_id)
                VALUES
                (:login, :password_hash, :phone, :email, :role_id)'
            );

            $stmt->execute([
                'login' => $data['login'],
                'password_hash' => $passwordHash,
                'phone' => $data['phone'],
                'email' => $data['email'],
                'role_id' => 1
            ]);

            $userId = (int) $db->lastInsertId();

            $stmt = $db->prepare(
                'INSERT INTO user_profiles
                (user_id, last_name, first_name, middle_name)
                VALUES
                (:user_id, :last_name, :first_name, :middle_name)'
            );

            $stmt->execute([
                'user_id' => $userId,
                'last_name' => $lastName,
                'first_name' => $firstName,
                'middle_name' => $middleName
            ]);

            $db->commit();

            return $userId;

        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            throw $e;
        }
    }
}
