<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241210105327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE site_steph_coach (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, description VARCHAR(255) NOT NULL, am_opening_time LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', pm_opening_time LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', roles JSON NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', update_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking CHANGE am_opening_time am_opening_time JSON DEFAULT NULL, CHANGE pm_opening_time pm_opening_time JSON DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE update_at update_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE picture CHANGE update_at update_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE prestation CHANGE update_at update_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE site_steph_coach');
        $this->addSql('ALTER TABLE booking CHANGE am_opening_time am_opening_time LONGTEXT DEFAULT NULL COLLATE `utf8mb4_bin`, CHANGE pm_opening_time pm_opening_time LONGTEXT DEFAULT NULL COLLATE `utf8mb4_bin`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, CHANGE update_at update_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE picture CHANGE update_at update_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE prestation CHANGE update_at update_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
