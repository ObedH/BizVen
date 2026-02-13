<?php
$dbFile = '/var/www/database/mydb.sqlite';
$db = new SQLite3($dbFile);

// Enable foreign keys
$db->exec("PRAGMA foreign_keys = ON;");

// Create tables
$db->exec("
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT DEFAULT 'user',
    certification_file TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS questions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
	title TEXT NOT NULL,
	content TEXT NOT NULL,
	user_id INTEGER NOT NULL,
	status TEXT DEFAULT 'open',
	created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CACADE
);

CREATE TABLE IF NOT EXISTS answers (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	question_id INTEGER NOT NULL,
	user_id INTEGER NOT NULL,
	content TEXT NOT NULL,
	is_approved INTEGER DEFAULT 0,
	created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY(question_id) REFERENCES questions(id) ON DELETE CACADE,
	FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);

");

echo "Tables created successfully!";
?>
