DROP TABLE IF EXISTS user;

CREATE TABLE user (
	id INT AUTO_INCREMENT PRIMARY KEY,
	email varchar(45) NOT NULL UNIQUE,
	password varchar(255) NOT NULL
);

INSERT INTO user (email, password) VALUES
('coach@equipe.fr', '$2y$10$NU0Bn3rHSzaA7.8nJLjQBeylKX4PRTjARP137Ol7sABIsCFt2Zc9e');