<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260517223827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creating pickup point table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE app_pickup_point (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, address LONGTEXT DEFAULT NULL, enabled TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_D6E9A46977153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_pickup_point_shipping_method (pickuppoint_id INT NOT NULL, shippingmethodinterface_id INT NOT NULL, INDEX IDX_82162ECDB422972 (pickuppoint_id), INDEX IDX_82162ECE2EE8AB0 (shippingmethodinterface_id), PRIMARY KEY(pickuppoint_id, shippingmethodinterface_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_pickup_point_shipping_method ADD CONSTRAINT FK_82162ECDB422972 FOREIGN KEY (pickuppoint_id) REFERENCES app_pickup_point (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_pickup_point_shipping_method ADD CONSTRAINT FK_82162ECE2EE8AB0 FOREIGN KEY (shippingmethodinterface_id) REFERENCES sylius_shipping_method (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_pickup_point_shipping_method DROP FOREIGN KEY FK_82162ECDB422972');
        $this->addSql('ALTER TABLE app_pickup_point_shipping_method DROP FOREIGN KEY FK_82162ECE2EE8AB0');
        $this->addSql('DROP TABLE app_pickup_point');
        $this->addSql('DROP TABLE app_pickup_point_shipping_method');
    }
}
