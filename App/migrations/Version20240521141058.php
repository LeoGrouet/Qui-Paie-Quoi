<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240521141058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Refactor entity and relation mapping';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users_groups DROP CONSTRAINT fk_ff8ab7e0a76ed395');
        $this->addSql('ALTER TABLE users_groups DROP CONSTRAINT fk_ff8ab7e0fe54d947');
        $this->addSql('DROP TABLE users_groups');
        $this->addSql('ALTER TABLE expense ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE expense DROP payer');
        $this->addSql('ALTER TABLE expense DROP date');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2D3A8DA6A76ED395 ON expense (user_id)');
        $this->addSql('ALTER TABLE "group" ALTER name TYPE VARCHAR(60)');
        $this->addSql('ALTER TABLE "user" ADD group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D649FE54D947 ON "user" (group_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE users_groups (user_id INT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(user_id, group_id))');
        $this->addSql('CREATE INDEX idx_ff8ab7e0fe54d947 ON users_groups (group_id)');
        $this->addSql('CREATE INDEX idx_ff8ab7e0a76ed395 ON users_groups (user_id)');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT fk_ff8ab7e0a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT fk_ff8ab7e0fe54d947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE expense DROP CONSTRAINT FK_2D3A8DA6A76ED395');
        $this->addSql('DROP INDEX UNIQ_2D3A8DA6A76ED395');
        $this->addSql('ALTER TABLE expense ADD payer VARCHAR(60) NOT NULL');
        $this->addSql('ALTER TABLE expense ADD date DATE NOT NULL');
        $this->addSql('ALTER TABLE expense DROP user_id');
        $this->addSql('ALTER TABLE "group" ALTER name TYPE INT');
        $this->addSql('ALTER TABLE "group" ALTER name TYPE INT');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649FE54D947');
        $this->addSql('DROP INDEX IDX_8D93D649FE54D947');
        $this->addSql('ALTER TABLE "user" DROP group_id');
    }
}
