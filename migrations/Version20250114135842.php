<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250114135842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity ADD category_activity_id INT NOT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A365B22FD FOREIGN KEY (category_activity_id) REFERENCES category_activity (id)');
        $this->addSql('CREATE INDEX IDX_AC74095A365B22FD ON activity (category_activity_id)');
        $this->addSql('ALTER TABLE avis ADD activity_id INT NOT NULL');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF081C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF081C06096 ON avis (activity_id)');
        $this->addSql('ALTER TABLE picture ADD activity_id INT NOT NULL');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F8981C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('CREATE INDEX IDX_16DB4F8981C06096 ON picture (activity_id)');
        $this->addSql('ALTER TABLE rendez_vous ADD activity_id INT NOT NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0A81C06096 ON rendez_vous (activity_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A365B22FD');
        $this->addSql('DROP INDEX IDX_AC74095A365B22FD ON activity');
        $this->addSql('ALTER TABLE activity DROP category_activity_id');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF081C06096');
        $this->addSql('DROP INDEX IDX_8F91ABF081C06096 ON avis');
        $this->addSql('ALTER TABLE avis DROP activity_id');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F8981C06096');
        $this->addSql('DROP INDEX IDX_16DB4F8981C06096 ON picture');
        $this->addSql('ALTER TABLE picture DROP activity_id');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A81C06096');
        $this->addSql('DROP INDEX IDX_65E8AA0A81C06096 ON rendez_vous');
        $this->addSql('ALTER TABLE rendez_vous DROP activity_id');
    }
}
