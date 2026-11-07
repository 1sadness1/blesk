PRAGMA foreign_keys = ON;
BEGIN TRANSACTION;

CREATE TABLE roles (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    login TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    phone TEXT NOT NULL,
    email TEXT NOT NULL,
    role_id INTEGER NOT NULL DEFAULT 1,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE user_profiles (
    user_id INTEGER PRIMARY KEY,
    last_name TEXT NOT NULL CHECK (trim(last_name) <> '' AND instr(last_name, ' ') = 0),
    first_name TEXT NOT NULL CHECK (trim(first_name) <> '' AND instr(first_name, ' ') = 0),
    middle_name TEXT CHECK (middle_name IS NULL OR
                            (trim(middle_name) <> '' AND instr(middle_name, ' ') = 0)),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE statuses (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE payment_methods (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE applications (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    address TEXT NOT NULL,
    contact_phone TEXT NOT NULL,
    desired_datetime TEXT NOT NULL,
    service_name TEXT NOT NULL,
    payment_method_id INTEGER NOT NULL,
    status_id INTEGER NOT NULL DEFAULT 1,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id),
    FOREIGN KEY (status_id) REFERENCES statuses(id)
);

CREATE TABLE reviews (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    application_id INTEGER NOT NULL UNIQUE,
    review_text TEXT NOT NULL,
    rating INTEGER CHECK (
        rating IS NULL OR
        (typeof(rating) = 'integer' AND rating BETWEEN 1 AND 5)
    ),
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (application_id)
        REFERENCES applications(id)
);

INSERT INTO roles (id, name) VALUES
    (1, 'Пользователь'),
    (2, 'Администратор');

INSERT INTO statuses (id, name) VALUES
    (1, 'Новая'),
    (2, 'В работе'),
    (3, 'Выполнено'),
    (4, 'Отменено');

INSERT INTO payment_methods (id, name) VALUES
    (1, 'Наличными'),
    (2, 'Банковской картой');


INSERT INTO users (
    login,
    password_hash,
    phone,
    email,
    role_id
)
VALUES (
    'Admin',
    '$2y$12$ygF4Ur91mfLmazHUNKwkFOWT/58JDK7/gQicIs5siQ4XkH1OzUory',
    '8(000)000-00-00',
    'admin@blesk.test',
    2
);

INSERT INTO user_profiles (
    user_id,
    last_name,
    first_name,
    middle_name
)
VALUES (
    (SELECT id FROM users WHERE login = 'Admin'),
    'Администратор',
    'Системный',
    NULL
);

COMMIT;