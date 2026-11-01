
PRAGMA foreign_keys = ON;

CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    login TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    full_name TEXT NOT NULL,
    phone TEXT NOT NULL,
    email TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'user' CHECK (role IN ('user', 'admin')),
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE statuses (
    id INTEGER PRIMARY KEY,
    code TEXT NOT NULL UNIQUE,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE payment_methods (
    id INTEGER PRIMARY KEY,
    code TEXT NOT NULL UNIQUE,
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
    user_id INTEGER NOT NULL,
    review_text TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES applications(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO statuses (id, code, name) VALUES
    (1, 'new', 'Новая'),
    (2, 'in_progress', 'В работе'),
    (3, 'completed', 'Выполнено'),
    (4, 'cancelled', 'Отменено');

INSERT INTO payment_methods (id, code, name) VALUES
    (1, 'cash', 'Наличными'),
    (2, 'card', 'Банковской картой');


