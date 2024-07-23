<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240723140857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurant CHANGE am_opening_time am_opening_time LONGTEXT NOT NULL, CHANGE pm_opening_time pm_opening_time LONGTEXT NOT NULL, CHANGE max_guest max_guest INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(32) NOT NULL, ADD lastname VARCHAR(64) NOT NULL, ADD guest_number SMALLINT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurant CHANGE am_opening_time am_opening_time LONGTEXT DEFAULT NULL, CHANGE pm_opening_time pm_opening_time LONGTEXT DEFAULT NULL, CHANGE max_guest max_guest INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP first_name, DROP lastname, DROP guest_number');
    }
}