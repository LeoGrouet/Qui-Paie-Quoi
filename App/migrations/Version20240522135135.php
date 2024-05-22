<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240522135135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE expense_group');
        $this->addSql('ALTER TABLE expense ADD group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2D3A8DA6FE54D947 ON expense (group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE expense_group (group_id INT NOT NULL, PRIMARY KEY(group_id))');
        $this->addSql('ALTER TABLE expense DROP CONSTRAINT FK_2D3A8DA6FE54D947');
        $this->addSql('DROP INDEX IDX_2D3A8DA6FE54D947');
        $this->addSql('ALTER TABLE expense DROP group_id');
    }
}
