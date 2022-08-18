<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220714230306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE envase (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, main_image_filename VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD envase_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD5206DA2E FOREIGN KEY (envase_id) REFERENCES envase (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD5206DA2E ON product (envase_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD5206DA2E');
        $this->addSql('DROP TABLE envase');
        $this->addSql('DROP INDEX IDX_D34A04AD5206DA2E ON product');
        $this->addSql('ALTER TABLE product DROP envase_id');
    }
}
