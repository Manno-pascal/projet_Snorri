<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231226085635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_user (event_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_92589AE271F7E88B (event_id), INDEX IDX_92589AE2A76ED395 (user_id), PRIMARY KEY(event_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE information (id INT AUTO_INCREMENT NOT NULL, user_creator_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_29791883C645C84A (user_creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, thread_id INT NOT NULL, user_creator_id INT DEFAULT NULL, content LONGTEXT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B6BD307FE2904019 (thread_id), INDEX IDX_B6BD307FC645C84A (user_creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, user_creator_id INT NOT NULL, title VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, salary VARCHAR(255) DEFAULT NULL, date DATETIME NOT NULL, description LONGTEXT NOT NULL, status VARCHAR(255) NOT NULL, contract_type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_29D6873EC645C84A (user_creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thread (id INT AUTO_INCREMENT NOT NULL, user_creator_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_31204C83C645C84A (user_creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tool (id INT AUTO_INCREMENT NOT NULL, user_sender_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, url VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, category VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_20F33ED1F6C43E79 (user_sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, siret VARCHAR(14) DEFAULT NULL, address VARCHAR(255) NOT NULL, domain VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, cv VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, contract_type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_thread (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, thread_id INT NOT NULL, INDEX IDX_547257BEA76ED395 (user_id), INDEX IDX_547257BEE2904019 (thread_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_tool (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tool_id INT NOT NULL, INDEX IDX_5A727218A76ED395 (user_id), INDEX IDX_5A7272188F7B22CC (tool_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE271F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE information ADD CONSTRAINT FK_29791883C645C84A FOREIGN KEY (user_creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FE2904019 FOREIGN KEY (thread_id) REFERENCES thread (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FC645C84A FOREIGN KEY (user_creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EC645C84A FOREIGN KEY (user_creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C83C645C84A FOREIGN KEY (user_creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tool ADD CONSTRAINT FK_20F33ED1F6C43E79 FOREIGN KEY (user_sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_thread ADD CONSTRAINT FK_547257BEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_thread ADD CONSTRAINT FK_547257BEE2904019 FOREIGN KEY (thread_id) REFERENCES thread (id)');
        $this->addSql('ALTER TABLE user_tool ADD CONSTRAINT FK_5A727218A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_tool ADD CONSTRAINT FK_5A7272188F7B22CC FOREIGN KEY (tool_id) REFERENCES tool (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_user DROP FOREIGN KEY FK_92589AE271F7E88B');
        $this->addSql('ALTER TABLE event_user DROP FOREIGN KEY FK_92589AE2A76ED395');
        $this->addSql('ALTER TABLE information DROP FOREIGN KEY FK_29791883C645C84A');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FE2904019');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FC645C84A');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EC645C84A');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C83C645C84A');
        $this->addSql('ALTER TABLE tool DROP FOREIGN KEY FK_20F33ED1F6C43E79');
        $this->addSql('ALTER TABLE user_thread DROP FOREIGN KEY FK_547257BEA76ED395');
        $this->addSql('ALTER TABLE user_thread DROP FOREIGN KEY FK_547257BEE2904019');
        $this->addSql('ALTER TABLE user_tool DROP FOREIGN KEY FK_5A727218A76ED395');
        $this->addSql('ALTER TABLE user_tool DROP FOREIGN KEY FK_5A7272188F7B22CC');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_user');
        $this->addSql('DROP TABLE information');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE thread');
        $this->addSql('DROP TABLE tool');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_thread');
        $this->addSql('DROP TABLE user_tool');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
