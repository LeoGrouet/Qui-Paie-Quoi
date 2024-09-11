<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20240902085345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Unique constraints on user_balance table on user_id and group_id';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F4F901F4A76ED395FE54D947 ON user_balance (user_id, group_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_F4F901F4A76ED395FE54D947');
    }
}
