<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240512232338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orders_details (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, prix NUMERIC(10, 0) NOT NULL, orders_id INT DEFAULT NULL, livres_id INT DEFAULT NULL, INDEX IDX_835379F1CFFE9AD6 (orders_id), INDEX IDX_835379F1EBF07F38 (livres_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE orders_details ADD CONSTRAINT FK_835379F1CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE orders_details ADD CONSTRAINT FK_835379F1EBF07F38 FOREIGN KEY (livres_id) REFERENCES livres (id)');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE6702C95E');
        $this->addSql('DROP TABLE orders');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, ref VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, quantite VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prix NUMERIC(10, 0) NOT NULL, id_livre_id INT NOT NULL, UNIQUE INDEX UNIQ_E52FFDEE6702C95E (id_livre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE6702C95E FOREIGN KEY (id_livre_id) REFERENCES livres (id)');
        $this->addSql('ALTER TABLE orders_details DROP FOREIGN KEY FK_835379F1CFFE9AD6');
        $this->addSql('ALTER TABLE orders_details DROP FOREIGN KEY FK_835379F1EBF07F38');
        $this->addSql('DROP TABLE orders_details');
    }
}
