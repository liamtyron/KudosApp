<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260511080058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__kudos AS SELECT id, msg_content, date_time FROM kudos');
        $this->addSql('DROP TABLE kudos');
        $this->addSql('CREATE TABLE kudos (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, msg_content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, sender_id INTEGER NOT NULL, receiver_id INTEGER NOT NULL, CONSTRAINT FK_1096D5FDF624B39D FOREIGN KEY (sender_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1096D5FDCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO kudos (id, msg_content, created_at) SELECT id, msg_content, date_time FROM __temp__kudos');
        $this->addSql('DROP TABLE __temp__kudos');
        $this->addSql('CREATE INDEX IDX_1096D5FDF624B39D ON kudos (sender_id)');
        $this->addSql('CREATE INDEX IDX_1096D5FDCD53EDB6 ON kudos (receiver_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, last_name, email, password, profile_pic FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, profile_pic VARCHAR(255) DEFAULT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL, first_name VARCHAR(50) NOT NULL)');
        $this->addSql('INSERT INTO user (id, last_name, email, password, profile_pic) SELECT id, last_name, email, password, profile_pic FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON user (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__kudos AS SELECT id, msg_content, created_at FROM kudos');
        $this->addSql('DROP TABLE kudos');
        $this->addSql('CREATE TABLE kudos (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, msg_content VARCHAR(255) NOT NULL, date_time DATETIME NOT NULL)');
        $this->addSql('INSERT INTO kudos (id, msg_content, date_time) SELECT id, msg_content, created_at FROM __temp__kudos');
        $this->addSql('DROP TABLE __temp__kudos');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, password, last_name, email, profile_pic FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, password VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, profile_pic VARCHAR(255) NOT NULL, firstname VARCHAR(10) NOT NULL, user_name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, password, last_name, email, profile_pic) SELECT id, password, last_name, email, profile_pic FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }
}
