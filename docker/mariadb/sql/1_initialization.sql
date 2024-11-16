CREATE USER IF NOT EXISTS 'social_installer_user'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON * . * TO 'social_installer_user'@'%';
FLUSH PRIVILEGES;

CREATE DATABASE IF NOT EXISTS social_installer_db;
