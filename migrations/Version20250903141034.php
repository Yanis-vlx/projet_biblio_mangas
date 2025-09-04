<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250903141034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE manga_author (manga_id INT NOT NULL, author_id INT NOT NULL, INDEX IDX_6EEF7ACF7B6461 (manga_id), INDEX IDX_6EEF7ACFF675F31B (author_id), PRIMARY KEY(manga_id, author_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE manga_author ADD CONSTRAINT FK_6EEF7ACF7B6461 FOREIGN KEY (manga_id) REFERENCES manga (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE manga_author ADD CONSTRAINT FK_6EEF7ACFF675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE manga ADD genre VARCHAR(255) NOT NULL, ADD prix VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE manga_author DROP FOREIGN KEY FK_6EEF7ACF7B6461');
        $this->addSql('ALTER TABLE manga_author DROP FOREIGN KEY FK_6EEF7ACFF675F31B');
        $this->addSql('DROP TABLE manga_author');
        $this->addSql('ALTER TABLE manga DROP genre, DROP prix');
    }
}
