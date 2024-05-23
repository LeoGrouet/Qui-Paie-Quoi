<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240523124411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE expenses_participants (expense_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(expense_id, user_id))');
        $this->addSql('CREATE INDEX IDX_81763EB0F395DB7B ON expenses_participants (expense_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81763EB0A76ED395 ON expenses_participants (user_id)');
        $this->addSql('CREATE TABLE "group" (id INT NOT NULL, name VARCHAR(60) NOT NULL, description VARCHAR(180) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, name VARCHAR(60) NOT NULL, email VARCHAR(60) NOT NULL, password VARCHAR(60) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE users_groups (user_id INT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(user_id, group_id))');
        $this->addSql('CREATE INDEX IDX_FF8AB7E0A76ED395 ON users_groups (user_id)');
        $this->addSql('CREATE INDEX IDX_FF8AB7E0FE54D947 ON users_groups (group_id)');
        $this->addSql('ALTER TABLE expenses_participants ADD CONSTRAINT FK_81763EB0F395DB7B FOREIGN KEY (expense_id) REFERENCES expense (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE expenses_participants ADD CONSTRAINT FK_81763EB0A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT FK_FF8AB7E0A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT FK_FF8AB7E0FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE expense ADD payer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE expense ADD group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE expense DROP payer');
        $this->addSql('ALTER TABLE expense DROP participants');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6C17AD9A9 FOREIGN KEY (payer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2D3A8DA6C17AD9A9 ON expense (payer_id)');
        $this->addSql('CREATE INDEX IDX_2D3A8DA6FE54D947 ON expense (group_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE expense DROP CONSTRAINT FK_2D3A8DA6FE54D947');
        $this->addSql('ALTER TABLE expense DROP CONSTRAINT FK_2D3A8DA6C17AD9A9');
        $this->addSql('DROP SEQUENCE group_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('ALTER TABLE expenses_participants DROP CONSTRAINT FK_81763EB0F395DB7B');
        $this->addSql('ALTER TABLE expenses_participants DROP CONSTRAINT FK_81763EB0A76ED395');
        $this->addSql('ALTER TABLE users_groups DROP CONSTRAINT FK_FF8AB7E0A76ED395');
        $this->addSql('ALTER TABLE users_groups DROP CONSTRAINT FK_FF8AB7E0FE54D947');
        $this->addSql('DROP TABLE expenses_participants');
        $this->addSql('DROP TABLE "group"');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE users_groups');
        $this->addSql('DROP INDEX IDX_2D3A8DA6C17AD9A9');
        $this->addSql('DROP INDEX IDX_2D3A8DA6FE54D947');
        $this->addSql('ALTER TABLE expense ADD payer VARCHAR(60) NOT NULL');
        $this->addSql('ALTER TABLE expense ADD participants TEXT NOT NULL');
        $this->addSql('ALTER TABLE expense DROP payer_id');
        $this->addSql('ALTER TABLE expense DROP group_id');
        $this->addSql('COMMENT ON COLUMN expense.participants IS \'(DC2Type:simple_array)\'');
    }
}
