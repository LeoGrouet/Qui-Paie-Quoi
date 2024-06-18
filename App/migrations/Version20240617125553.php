<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240617125553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Delete the password from user entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" DROP password');
        $this->addSql('ALTER TABLE "user" ALTER name TYPE VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD password VARCHAR(60) NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER name TYPE VARCHAR(60)');
    }
}
