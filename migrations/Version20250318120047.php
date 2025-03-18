<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250318120047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE request_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE daily_quiz_progress_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE daily_quiz_progress (id INT NOT NULL, user_id INT NOT NULL, date DATE NOT NULL, total_answered INT NOT NULL, correct_answers INT NOT NULL, daily_goal INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_264552B5A76ED395 ON daily_quiz_progress (user_id)');
        $this->addSql('ALTER TABLE daily_quiz_progress ADD CONSTRAINT FK_264552B5A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE request');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE daily_quiz_progress_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE request (id INT NOT NULL, route VARCHAR(255) NOT NULL, data TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_main_resquest BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN request.data IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN request.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE daily_quiz_progress DROP CONSTRAINT FK_264552B5A76ED395');
        $this->addSql('DROP TABLE daily_quiz_progress');
    }
}
