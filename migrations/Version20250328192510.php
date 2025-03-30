<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328192510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE quiz_session_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE quiz_session (id INT NOT NULL, user_id INT NOT NULL, current_lives INT NOT NULL, max_lives INT NOT NULL, current_streak INT NOT NULL, best_streak INT NOT NULL, difficulty VARCHAR(20) NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, completed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_completed BOOLEAN NOT NULL, is_won BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C21E7874A76ED395 ON quiz_session (user_id)');
        $this->addSql('COMMENT ON COLUMN quiz_session.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN quiz_session.completed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE quiz_session ADD CONSTRAINT FK_C21E7874A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE quiz_session_id_seq CASCADE');
        $this->addSql('ALTER TABLE quiz_session DROP CONSTRAINT FK_C21E7874A76ED395');
        $this->addSql('DROP TABLE quiz_session');
    }
}
