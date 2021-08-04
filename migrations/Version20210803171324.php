<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210803171324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, zip_code INT NOT NULL, name VARCHAR(60) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, property_id INT DEFAULT NULL, name VARCHAR(128) NOT NULL, path VARCHAR(255) NOT NULL, INDEX IDX_C53D045F549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, property_id INT DEFAULT NULL, title VARCHAR(40) NOT NULL, slug VARCHAR(64) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_29D6873E549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE option_property (option_id INT NOT NULL, property_id INT NOT NULL, INDEX IDX_AB856D7AA7C41D6F (option_id), INDEX IDX_AB856D7A549213EC (property_id), PRIMARY KEY(option_id, property_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, property_type_id INT NOT NULL, transaction_type VARCHAR(15) NOT NULL, manager VARCHAR(45) NOT NULL, area INT NOT NULL, rooms INT NOT NULL, price INT NOT NULL, energy VARCHAR(3) NOT NULL, ges VARCHAR(3) NOT NULL, INDEX IDX_8BF21CDE8BAC62AF (city_id), INDEX IDX_8BF21CDE9C81C6EB (property_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE option_property ADD CONSTRAINT FK_AB856D7AA7C41D6F FOREIGN KEY (option_id) REFERENCES `option` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE option_property ADD CONSTRAINT FK_AB856D7A549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE9C81C6EB FOREIGN KEY (property_type_id) REFERENCES property_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE8BAC62AF');
        $this->addSql('ALTER TABLE option_property DROP FOREIGN KEY FK_AB856D7AA7C41D6F');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F549213EC');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E549213EC');
        $this->addSql('ALTER TABLE option_property DROP FOREIGN KEY FK_AB856D7A549213EC');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE9C81C6EB');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE option_property');
        $this->addSql('DROP TABLE property');
        $this->addSql('DROP TABLE property_type');
    }
}
