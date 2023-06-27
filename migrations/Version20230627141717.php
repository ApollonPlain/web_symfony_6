<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230627141717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE quiz_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE quiz (id INT NOT NULL, question TEXT NOT NULL, answer_a TEXT DEFAULT NULL, is_a BOOLEAN DEFAULT NULL, answer_b TEXT DEFAULT NULL, is_b BOOLEAN DEFAULT NULL, answer_c TEXT DEFAULT NULL, is_c BOOLEAN DEFAULT NULL, answer_d TEXT DEFAULT NULL, is_d BOOLEAN DEFAULT NULL, answer_e TEXT DEFAULT NULL, is_e BOOLEAN DEFAULT NULL, answer_f TEXT DEFAULT NULL, is_f BOOLEAN DEFAULT NULL, answer_g TEXT DEFAULT NULL, is_g BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE quiz_id_seq CASCADE');
        $this->addSql('DROP TABLE quiz');
    }
}
