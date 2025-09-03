<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250903095304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE author CHANGE date_of_birth date_of_birth DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE manga ADD genre VARCHAR(255) NOT NULL, CHANGE isbn isbn VARCHAR(255) NOT NULL, CHANGE cover cover VARCHAR(255) NOT NULL, CHANGE plot plot LONGTEXT NOT NULL, CHANGE page_number page_number INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE author CHANGE date_of_birth date_of_birth DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE manga DROP genre, CHANGE isbn isbn VARCHAR(13) DEFAULT NULL, CHANGE cover cover VARCHAR(255) DEFAULT NULL, CHANGE plot plot VARCHAR(255) DEFAULT NULL, CHANGE page_number page_number INT DEFAULT NULL');
    }
}
