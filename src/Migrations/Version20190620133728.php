<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190620133728 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE dates_evenements (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, INDEX IDX_A00387DD71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, texte LONGTEXT NOT NULL, tarif_moins_de12 DOUBLE PRECISION NOT NULL, plus_de12 DOUBLE PRECISION NOT NULL, proprietaire DOUBLE PRECISION NOT NULL, nb_max_participants INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_galops (event_id INT NOT NULL, galops_id INT NOT NULL, INDEX IDX_E6B02A3871F7E88B (event_id), INDEX IDX_E6B02A386EA63067 (galops_id), PRIMARY KEY(event_id, galops_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE galops (id INT AUTO_INCREMENT NOT NULL, niveau INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, galop_id INT NOT NULL, email VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, INDEX IDX_1D1C63B34A6A299 (galop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dates_evenements ADD CONSTRAINT FK_A00387DD71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_galops ADD CONSTRAINT FK_E6B02A3871F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_galops ADD CONSTRAINT FK_E6B02A386EA63067 FOREIGN KEY (galops_id) REFERENCES galops (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B34A6A299 FOREIGN KEY (galop_id) REFERENCES galops (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dates_evenements DROP FOREIGN KEY FK_A00387DD71F7E88B');
        $this->addSql('ALTER TABLE event_galops DROP FOREIGN KEY FK_E6B02A3871F7E88B');
        $this->addSql('ALTER TABLE event_galops DROP FOREIGN KEY FK_E6B02A386EA63067');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B34A6A299');
        $this->addSql('DROP TABLE dates_evenements');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_galops');
        $this->addSql('DROP TABLE galops');
        $this->addSql('DROP TABLE utilisateur');
    }
}
