<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240528141039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE expenses_users (expense_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(expense_id, user_id))');
        $this->addSql('CREATE INDEX IDX_5009CB88F395DB7B ON expenses_users (expense_id)');
        $this->addSql('CREATE INDEX IDX_5009CB88A76ED395 ON expenses_users (user_id)');
        $this->addSql('ALTER TABLE expenses_users ADD CONSTRAINT FK_5009CB88F395DB7B FOREIGN KEY (expense_id) REFERENCES expense (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE expenses_users ADD CONSTRAINT FK_5009CB88A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE expenses_participants DROP CONSTRAINT fk_81763eb0f395db7b');
        $this->addSql('ALTER TABLE expenses_participants DROP CONSTRAINT fk_81763eb0a76ed395');
        $this->addSql('DROP TABLE expenses_participants');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE expenses_participants (expense_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(expense_id, user_id))');
        $this->addSql('CREATE INDEX idx_81763eb0a76ed395 ON expenses_participants (user_id)');
        $this->addSql('CREATE INDEX idx_81763eb0f395db7b ON expenses_participants (expense_id)');
        $this->addSql('ALTER TABLE expenses_participants ADD CONSTRAINT fk_81763eb0f395db7b FOREIGN KEY (expense_id) REFERENCES expense (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE expenses_participants ADD CONSTRAINT fk_81763eb0a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE expenses_users DROP CONSTRAINT FK_5009CB88F395DB7B');
        $this->addSql('ALTER TABLE expenses_users DROP CONSTRAINT FK_5009CB88A76ED395');
        $this->addSql('DROP TABLE expenses_users');
    }
}
