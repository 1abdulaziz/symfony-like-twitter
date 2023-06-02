<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230521194138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD auther_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CBCC5EBBA FOREIGN KEY (auther_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9474526CBCC5EBBA ON comment (auther_id)');
        $this->addSql('ALTER TABLE micro_post ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE micro_post ADD CONSTRAINT FK_2AEFE017F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2AEFE017F675F31B ON micro_post (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CBCC5EBBA');
        $this->addSql('DROP INDEX IDX_9474526CBCC5EBBA ON comment');
        $this->addSql('ALTER TABLE comment DROP auther_id');
        $this->addSql('ALTER TABLE micro_post DROP FOREIGN KEY FK_2AEFE017F675F31B');
        $this->addSql('DROP INDEX IDX_2AEFE017F675F31B ON micro_post');
        $this->addSql('ALTER TABLE micro_post DROP author_id');
    }
}
