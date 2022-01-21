<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211017165721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_4FBF094F5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_offer (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, main_technology_id INT NOT NULL, title VARCHAR(255) NOT NULL, source VARCHAR(255) NOT NULL, url VARCHAR(1000) NOT NULL, external_id VARCHAR(1000) NOT NULL, published_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_288A3A4E979B1AD6 (company_id), INDEX IDX_288A3A4EF09167FE (main_technology_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_offer_salary (id INT AUTO_INCREMENT NOT NULL, job_offer_id INT NOT NULL, contract_type VARCHAR(255) DEFAULT NULL, `from` INT DEFAULT NULL, `to` INT DEFAULT NULL, currency VARCHAR(100) DEFAULT NULL, entry_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_422559FF3481D195 (job_offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technology (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_F463524D5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4E979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4EF09167FE FOREIGN KEY (main_technology_id) REFERENCES technology (id)');
        $this->addSql('ALTER TABLE job_offer_salary ADD CONSTRAINT FK_422559FF3481D195 FOREIGN KEY (job_offer_id) REFERENCES job_offer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4E979B1AD6');
        $this->addSql('ALTER TABLE job_offer_salary DROP FOREIGN KEY FK_422559FF3481D195');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4EF09167FE');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE job_offer');
        $this->addSql('DROP TABLE job_offer_salary');
        $this->addSql('DROP TABLE technology');
    }
}
