<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251213170254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE day (id INT AUTO_INCREMENT NOT NULL, month_id INT NOT NULL, date INT NOT NULL, weekend_day TINYINT(1) NOT NULL, INDEX IDX_E5A02990A0CBDE4 (month_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE month (id INT AUTO_INCREMENT NOT NULL, year_id INT NOT NULL, month INT NOT NULL, INDEX IDX_8EB6100640C1FEA7 (year_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE year (id INT AUTO_INCREMENT NOT NULL, year INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE day ADD CONSTRAINT FK_E5A02990A0CBDE4 FOREIGN KEY (month_id) REFERENCES month (id)');
        $this->addSql('ALTER TABLE month ADD CONSTRAINT FK_8EB6100640C1FEA7 FOREIGN KEY (year_id) REFERENCES year (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE day DROP FOREIGN KEY FK_E5A02990A0CBDE4');
        $this->addSql('ALTER TABLE month DROP FOREIGN KEY FK_8EB6100640C1FEA7');
        $this->addSql('DROP TABLE day');
        $this->addSql('DROP TABLE month');
        $this->addSql('DROP TABLE year');
    }
}
