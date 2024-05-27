<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240527081331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update expense table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX uniq_81763eb0a76ed395');
        $this->addSql('CREATE INDEX IDX_81763EB0A76ED395 ON expenses_participants (user_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX IDX_81763EB0A76ED395');
        $this->addSql('CREATE UNIQUE INDEX uniq_81763eb0a76ed395 ON expenses_participants (user_id)');
    }
}
