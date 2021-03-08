BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS "User" (
	"user_ID"	INTEGER NOT NULL UNIQUE,
	"user_fName"	TEXT NOT NULL,
	"user_lName"	TEXT NOT NULL,
	"user_type"	TEXT DEFAULT 'Student',
	"user_password"	TEXT NOT NULL,
	"user_email"	TEXT NOT NULL UNIQUE,
	PRIMARY KEY("user_ID" AUTOINCREMENT)
);
INSERT INTO "User" VALUES (1,'Primary','Admin','teacher','$2y$10$UUI2Vk2ZNtx5KP3XqOd5XepbajelK5tB1.Pn3tOfKtRhMjQG7IPPG','admin@admin.com');
COMMIT;
