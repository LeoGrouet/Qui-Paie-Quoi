<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240527084519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update relation mapping between group and user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE groups_users (group_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(group_id, user_id))');
        $this->addSql('CREATE INDEX IDX_4520C24DFE54D947 ON groups_users (group_id)');
        $this->addSql('CREATE INDEX IDX_4520C24DA76ED395 ON groups_users (user_id)');
        $this->addSql('ALTER TABLE groups_users ADD CONSTRAINT FK_4520C24DFE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE groups_users ADD CONSTRAINT FK_4520C24DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_groups DROP CONSTRAINT fk_ff8ab7e0a76ed395');
        $this->addSql('ALTER TABLE users_groups DROP CONSTRAINT fk_ff8ab7e0fe54d947');
        $this->addSql('DROP TABLE users_groups');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE users_groups (user_id INT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(user_id, group_id))');
        $this->addSql('CREATE INDEX idx_ff8ab7e0fe54d947 ON users_groups (group_id)');
        $this->addSql('CREATE INDEX idx_ff8ab7e0a76ed395 ON users_groups (user_id)');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT fk_ff8ab7e0a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT fk_ff8ab7e0fe54d947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE groups_users DROP CONSTRAINT FK_4520C24DFE54D947');
        $this->addSql('ALTER TABLE groups_users DROP CONSTRAINT FK_4520C24DA76ED395');
        $this->addSql('DROP TABLE groups_users');
    }
}
