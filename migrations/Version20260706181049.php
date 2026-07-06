<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260706181049 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\SQLitePlatform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\SQLitePlatform'."
        );

        $this->addSql('CREATE TABLE link (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, short_url VARCHAR(255) NOT NULL COLLATE "BINARY", long_url VARCHAR(255) NOT NULL COLLATE "BINARY", creation_time DATETIME NOT NULL, last_use_time DATETIME DEFAULT NULL, use_count INTEGER NOT NULL)');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\SQLitePlatform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\SQLitePlatform'."
        );

        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL COLLATE "BINARY", headers CLOB NOT NULL COLLATE "BINARY", queue_name VARCHAR(190) NOT NULL COLLATE "BINARY", created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages (queue_name, available_at, delivered_at, id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\SQLitePlatform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\SQLitePlatform'."
        );

        $this->addSql('DROP TABLE "link"');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\SQLitePlatform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\SQLitePlatform'."
        );

        $this->addSql('DROP TABLE "messenger_messages"');
    }
}
