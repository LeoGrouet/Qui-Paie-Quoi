<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240523091401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'creation of association table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD expense_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649F395DB7B FOREIGN KEY (expense_id) REFERENCES expense (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D649F395DB7B ON "user" (expense_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649F395DB7B');
        $this->addSql('DROP INDEX IDX_8D93D649F395DB7B');
        $this->addSql('ALTER TABLE "user" DROP expense_id');
    }
}
