<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260707065605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE link ADD COLUMN is_one_time BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE link ADD COLUMN expiry_date DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__link AS SELECT id, short_url, long_url, creation_time, last_use_time, use_count, owner_id FROM link');
        $this->addSql('DROP TABLE link');
        $this->addSql('CREATE TABLE link (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, short_url VARCHAR(255) NOT NULL, long_url VARCHAR(255) NOT NULL, creation_time DATETIME NOT NULL, last_use_time DATETIME DEFAULT NULL, use_count INTEGER NOT NULL, owner_id INTEGER NOT NULL, CONSTRAINT FK_36AC99F17E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO link (id, short_url, long_url, creation_time, last_use_time, use_count, owner_id) SELECT id, short_url, long_url, creation_time, last_use_time, use_count, owner_id FROM __temp__link');
        $this->addSql('DROP TABLE __temp__link');
        $this->addSql('CREATE INDEX IDX_36AC99F17E3C61F9 ON link (owner_id)');
    }
}
