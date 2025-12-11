<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251211212115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE day CHANGE month month_id INT NOT NULL');
        $this->addSql('ALTER TABLE day ADD CONSTRAINT FK_E5A02990A0CBDE4 FOREIGN KEY (month_id) REFERENCES month (id)');
        $this->addSql('CREATE INDEX IDX_E5A02990A0CBDE4 ON day (month_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE day DROP FOREIGN KEY FK_E5A02990A0CBDE4');
        $this->addSql('DROP INDEX IDX_E5A02990A0CBDE4 ON day');
        $this->addSql('ALTER TABLE day CHANGE month_id month INT NOT NULL');
    }
}
