<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260213153443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock_article ADD article_id INT NOT NULL');
        $this->addSql('ALTER TABLE stock_article ADD CONSTRAINT FK_1B9C1EC57294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_1B9C1EC57294869C ON stock_article (article_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock_article DROP FOREIGN KEY FK_1B9C1EC57294869C');
        $this->addSql('DROP INDEX IDX_1B9C1EC57294869C ON stock_article');
        $this->addSql('ALTER TABLE stock_article DROP article_id');
    }
}
