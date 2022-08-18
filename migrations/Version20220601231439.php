<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220601231439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coupon (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, discount DOUBLE PRECISION NOT NULL, date_to DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, cellphone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_zone (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price_delivery DOUBLE PRECISION NOT NULL, price_pickup DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, coupon_id INT DEFAULT NULL, zone_id INT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, created DATE NOT NULL, status VARCHAR(255) NOT NULL, delivery_address VARCHAR(255) NOT NULL, delivery_city VARCHAR(255) NOT NULL, delivery_phone VARCHAR(255) NOT NULL, invoice_email VARCHAR(255) NOT NULL, pickup_refillments TINYINT(1) NOT NULL, pickup_address VARCHAR(255) NOT NULL, pickup_city VARCHAR(255) NOT NULL, pickup_phone VARCHAR(255) NOT NULL, pickup_price DOUBLE PRECISION DEFAULT NULL, INDEX IDX_F52993989395C3F3 (customer_id), INDEX IDX_F529939866C5951B (coupon_id), INDEX IDX_F52993989F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_product (order_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_2530ADE68D9F6D38 (order_id), INDEX IDX_2530ADE64584665A (product_id), PRIMARY KEY(order_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993989395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939866C5951B FOREIGN KEY (coupon_id) REFERENCES coupon (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993989F2C3FAB FOREIGN KEY (zone_id) REFERENCES delivery_zone (id)');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE68D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE64584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939866C5951B');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993989395C3F3');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993989F2C3FAB');
        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE68D9F6D38');
        $this->addSql('DROP TABLE coupon');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE delivery_zone');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_product');
    }
}
