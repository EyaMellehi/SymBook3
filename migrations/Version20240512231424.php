<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240512231424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, ref VARCHAR(255) NOT NULL, quantite VARCHAR(255) NOT NULL, prix NUMERIC(10, 0) NOT NULL, id_livre_id INT NOT NULL, UNIQUE INDEX UNIQ_E52FFDEE6702C95E (id_livre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE6702C95E FOREIGN KEY (id_livre_id) REFERENCES livres (id)');
        $this->addSql('ALTER TABLE detail DROP FOREIGN KEY fk');
        $this->addSql('DROP TABLE detail');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE detail (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, prix NUMERIC(11, 0) NOT NULL, livre_id INT NOT NULL, INDEX fk (livre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE detail ADD CONSTRAINT fk FOREIGN KEY (livre_id) REFERENCES livres (id)');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE6702C95E');
        $this->addSql('DROP TABLE orders');
    }
}
