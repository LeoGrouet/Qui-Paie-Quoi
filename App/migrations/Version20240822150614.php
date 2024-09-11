<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20240822150614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add user balance entity and relation between user, group and user balance';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE user_balance_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_balance (id INT NOT NULL, user_id INT DEFAULT NULL, group_id INT DEFAULT NULL, amount INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F4F901F4A76ED395 ON user_balance (user_id)');
        $this->addSql('CREATE INDEX IDX_F4F901F4FE54D947 ON user_balance (group_id)');
        $this->addSql('ALTER TABLE user_balance ADD CONSTRAINT FK_F4F901F4A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_balance ADD CONSTRAINT FK_F4F901F4FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE user_balance_id_seq CASCADE');
        $this->addSql('ALTER TABLE user_balance DROP CONSTRAINT FK_F4F901F4A76ED395');
        $this->addSql('ALTER TABLE user_balance DROP CONSTRAINT FK_F4F901F4FE54D947');
        $this->addSql('DROP TABLE user_balance');
    }
}
