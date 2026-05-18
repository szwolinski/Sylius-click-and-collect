<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260518025533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_shipment ADD pickup_point_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_shipment ADD CONSTRAINT FK_FD707B33682033F1 FOREIGN KEY (pickup_point_id) REFERENCES app_pickup_point (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_FD707B33682033F1 ON sylius_shipment (pickup_point_id)');
        $this->addSql('ALTER TABLE sylius_shipping_method DROP FOREIGN KEY FK_5FB0EE11682033F1');
        $this->addSql('DROP INDEX IDX_5FB0EE11682033F1 ON sylius_shipping_method');
        $this->addSql('ALTER TABLE sylius_shipping_method DROP pickup_point_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_shipment DROP FOREIGN KEY FK_FD707B33682033F1');
        $this->addSql('DROP INDEX IDX_FD707B33682033F1 ON sylius_shipment');
        $this->addSql('ALTER TABLE sylius_shipment DROP pickup_point_id');
        $this->addSql('ALTER TABLE sylius_shipping_method ADD pickup_point_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_shipping_method ADD CONSTRAINT FK_5FB0EE11682033F1 FOREIGN KEY (pickup_point_id) REFERENCES app_pickup_point (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_5FB0EE11682033F1 ON sylius_shipping_method (pickup_point_id)');
    }
}
