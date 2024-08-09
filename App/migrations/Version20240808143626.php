<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240808143626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'User email length increased to 320';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ALTER email TYPE VARCHAR(320)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ALTER email TYPE VARCHAR(255)');
    }
}
