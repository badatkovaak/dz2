<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260706084738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__link AS SELECT id, short_url, long_url, creation_time, last_used_time, use_count FROM link');
        $this->addSql('DROP TABLE link');
        $this->addSql('CREATE TABLE link (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, short_url VARCHAR(255) NOT NULL, long_url VARCHAR(255) NOT NULL, creation_time DATETIME NOT NULL, last_use_time DATETIME DEFAULT NULL, use_count INTEGER NOT NULL)');
        $this->addSql('INSERT INTO link (id, short_url, long_url, creation_time, last_use_time, use_count) SELECT id, short_url, long_url, creation_time, last_used_time, use_count FROM __temp__link');
        $this->addSql('DROP TABLE __temp__link');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__link AS SELECT id, short_url, long_url, creation_time, last_use_time, use_count FROM link');
        $this->addSql('DROP TABLE link');
        $this->addSql('CREATE TABLE link (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, short_url VARCHAR(255) NOT NULL, long_url VARCHAR(255) NOT NULL, creation_time DATETIME NOT NULL, last_used_time DATETIME DEFAULT NULL, use_count INTEGER NOT NULL)');
        $this->addSql('INSERT INTO link (id, short_url, long_url, creation_time, last_used_time, use_count) SELECT id, short_url, long_url, creation_time, last_use_time, use_count FROM __temp__link');
        $this->addSql('DROP TABLE __temp__link');
    }
}
