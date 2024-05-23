<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240523112548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'update relation table users-expenses';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE expenses_users (expense_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(expense_id, user_id))');
        $this->addSql('CREATE INDEX IDX_5009CB88F395DB7B ON expenses_users (expense_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5009CB88A76ED395 ON expenses_users (user_id)');
        $this->addSql('ALTER TABLE expenses_users ADD CONSTRAINT FK_5009CB88F395DB7B FOREIGN KEY (expense_id) REFERENCES expense (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE expenses_users ADD CONSTRAINT FK_5009CB88A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE expenses_id DROP CONSTRAINT fk_2055804af395db7b');
        $this->addSql('ALTER TABLE expenses_id DROP CONSTRAINT fk_2055804aa76ed395');
        $this->addSql('DROP TABLE expenses_id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE expenses_id (expense_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(expense_id, user_id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_2055804aa76ed395 ON expenses_id (user_id)');
        $this->addSql('CREATE INDEX idx_2055804af395db7b ON expenses_id (expense_id)');
        $this->addSql('ALTER TABLE expenses_id ADD CONSTRAINT fk_2055804af395db7b FOREIGN KEY (expense_id) REFERENCES expense (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE expenses_id ADD CONSTRAINT fk_2055804aa76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE expenses_users DROP CONSTRAINT FK_5009CB88F395DB7B');
        $this->addSql('ALTER TABLE expenses_users DROP CONSTRAINT FK_5009CB88A76ED395');
        $this->addSql('DROP TABLE expenses_users');
    }
}
