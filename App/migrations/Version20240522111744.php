<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240522111744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update expense - user relation';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE expense_user (user_id INT NOT NULL, PRIMARY KEY(user_id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE expense_user');
    }
}
