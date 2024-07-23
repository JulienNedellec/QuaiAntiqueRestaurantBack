<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240721181905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(64) NOT NULL, slug VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, restaurant_id INT NOT NULL, INDEX IDX_16DB4F89B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F89B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE restaurant ADD name VARCHAR(64) NOT NULL, ADD description LONGTEXT NOT NULL, ADD am_opening_time LONGTEXT NOT NULL, ADD pm_opening_time LONGTEXT NOT NULL, ADD max_guest INT NOT NULL, ADD created_at DATETIME NOT NULL, ADD update_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD created_at DATETIME NOT NULL, ADD update_at DATETIME DEFAULT NULL, ADD api_token VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F89B1E7706E');
        $this->addSql('DROP TABLE picture');
        $this->addSql('ALTER TABLE restaurant DROP name, DROP description, DROP am_opening_time, DROP pm_opening_time, DROP max_guest, DROP created_at, DROP update_at');
        $this->addSql('ALTER TABLE user DROP created_at, DROP update_at, DROP api_token');
    }
}
