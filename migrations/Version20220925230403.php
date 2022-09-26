<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220925230403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room ADD max_round INT NOT NULL, ADD visibilty TINYINT(1) NOT NULL, ADD current_round INT DEFAULT NULL, ADD player_ready TINYINT(1) DEFAULT NULL, ADD host_ready TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(12) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room DROP max_round, DROP visibilty, DROP current_round, DROP player_ready, DROP host_ready');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL');
    }
}
