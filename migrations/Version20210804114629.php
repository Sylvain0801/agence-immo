<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210804114629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image_property (image_id INT NOT NULL, property_id INT NOT NULL, INDEX IDX_40292763DA5256D (image_id), INDEX IDX_4029276549213EC (property_id), PRIMARY KEY(image_id, property_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image_property ADD CONSTRAINT FK_40292763DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_property ADD CONSTRAINT FK_4029276549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F549213EC');
        $this->addSql('DROP INDEX IDX_C53D045F549213EC ON image');
        $this->addSql('ALTER TABLE image DROP property_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE image_property');
        $this->addSql('ALTER TABLE image ADD property_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C53D045F549213EC ON image (property_id)');
    }
}
