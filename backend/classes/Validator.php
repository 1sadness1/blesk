<?php

declare(strict_types=1);

class Validator
{
    public static function validateRegistration(array $data): array
    {
        $errors = [];

        if (!preg_match('/^[A-Za-z0-9]{6,}$/', $data['login'] ?? '')) {
            $errors['login'] = 'Логин: минимум 6 символов, только латиница и цифры';
        }

        $password = $data['password'] ?? '';
        if (!preg_match('/^.{8,}$/us', $password) || !preg_match('/[0-9]/', $password)) {
            $errors['password'] = 'Пароль: минимум 8 символов и одна цифра';
        }

        $fullName = trim((string)($data['full_name'] ?? ''));
        if (!preg_match('/^[А-Яа-яЁё]+(?:\s+[А-Яа-яЁё]+){2}$/u', $fullName)) {
            $errors['full_name'] = 'Введите фамилию, имя и отчество (3 слова, кириллица)';
        }

        if (!preg_match('/^8\(\d{3}\)\d{3}-\d{2}-\d{2}$/', $data['phone'] ?? '')) {
            $errors['phone'] = 'Формат телефона: 8(999)123-45-67';
        }

        if (!filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Некорректный адрес электронной почты';
        }

        return $errors;
    }
}
