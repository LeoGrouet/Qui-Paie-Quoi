<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240426133646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update participants type into simple_array';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE expense ALTER participants TYPE TEXT');
        $this->addSql('COMMENT ON COLUMN expense.participants IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE expense ALTER participants TYPE TEXT');
        $this->addSql('COMMENT ON COLUMN expense.participants IS \'(DC2Type:array)\'');
    }
}
