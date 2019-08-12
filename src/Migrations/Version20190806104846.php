<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190806104846 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE attribut_moyen_paiements (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE creneaux_benevoles (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, nb_benevoles INT NOT NULL, INDEX IDX_B135227271F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dates_evenements (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, INDEX IDX_A00387DD71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE description (id INT AUTO_INCREMENT NOT NULL, texte LONGTEXT DEFAULT NULL, updated_at DATETIME NOT NULL, image_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, texte LONGTEXT NOT NULL, tarif_moins_de12 DOUBLE PRECISION NOT NULL, tarif_plus_de12 DOUBLE PRECISION NOT NULL, tarif_proprietaire DOUBLE PRECISION NOT NULL, nb_max_participants INT NOT NULL, repas_possible TINYINT(1) DEFAULT NULL, divers TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_galops (event_id INT NOT NULL, galops_id INT NOT NULL, INDEX IDX_E6B02A3871F7E88B (event_id), INDEX IDX_E6B02A386EA63067 (galops_id), PRIMARY KEY(event_id, galops_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE files_pdf (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE galops (id INT AUTO_INCREMENT NOT NULL, niveau INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, evenement_id INT NOT NULL, image_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL, INDEX IDX_E01FBE6AFD02F13 (evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, galop_id INT NOT NULL, email VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, roles JSON NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, date_naissance DATETIME NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL, image_name VARCHAR(255) DEFAULT NULL, validation_email_token VARCHAR(255) DEFAULT NULL, verified_mail TINYINT(1) DEFAULT NULL, INDEX IDX_1D1C63B34A6A299 (galop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participe (utilisateur_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_9FFA8D4FB88E14F (utilisateur_id), INDEX IDX_9FFA8D471F7E88B (event_id), PRIMARY KEY(utilisateur_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur_event (utilisateur_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_FD6B5279FB88E14F (utilisateur_id), INDEX IDX_FD6B527971F7E88B (event_id), PRIMARY KEY(utilisateur_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur_creneaux_benevoles (utilisateur_id INT NOT NULL, creneaux_benevoles_id INT NOT NULL, INDEX IDX_7D1BEE82FB88E14F (utilisateur_id), INDEX IDX_7D1BEE82C035FBBC (creneaux_benevoles_id), PRIMARY KEY(utilisateur_id, creneaux_benevoles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur_moyen_paiement_event (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, attribut_moyen_paiement_id INT DEFAULT NULL, event_id INT DEFAULT NULL, INDEX IDX_F823F1CFB88E14F (utilisateur_id), INDEX IDX_F823F1CA7823375 (attribut_moyen_paiement_id), INDEX IDX_F823F1C71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, evenement_id INT NOT NULL, lien VARCHAR(255) NOT NULL, INDEX IDX_7CC7DA2CFD02F13 (evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE creneaux_benevoles ADD CONSTRAINT FK_B135227271F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE dates_evenements ADD CONSTRAINT FK_A00387DD71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_galops ADD CONSTRAINT FK_E6B02A3871F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_galops ADD CONSTRAINT FK_E6B02A386EA63067 FOREIGN KEY (galops_id) REFERENCES galops (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6AFD02F13 FOREIGN KEY (evenement_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B34A6A299 FOREIGN KEY (galop_id) REFERENCES galops (id)');
        $this->addSql('ALTER TABLE participe ADD CONSTRAINT FK_9FFA8D4FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participe ADD CONSTRAINT FK_9FFA8D471F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_event ADD CONSTRAINT FK_FD6B5279FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_event ADD CONSTRAINT FK_FD6B527971F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_creneaux_benevoles ADD CONSTRAINT FK_7D1BEE82FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_creneaux_benevoles ADD CONSTRAINT FK_7D1BEE82C035FBBC FOREIGN KEY (creneaux_benevoles_id) REFERENCES creneaux_benevoles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_moyen_paiement_event ADD CONSTRAINT FK_F823F1CFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur_moyen_paiement_event ADD CONSTRAINT FK_F823F1CA7823375 FOREIGN KEY (attribut_moyen_paiement_id) REFERENCES attribut_moyen_paiements (id)');
        $this->addSql('ALTER TABLE utilisateur_moyen_paiement_event ADD CONSTRAINT FK_F823F1C71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CFD02F13 FOREIGN KEY (evenement_id) REFERENCES event (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE utilisateur_moyen_paiement_event DROP FOREIGN KEY FK_F823F1CA7823375');
        $this->addSql('ALTER TABLE utilisateur_creneaux_benevoles DROP FOREIGN KEY FK_7D1BEE82C035FBBC');
        $this->addSql('ALTER TABLE creneaux_benevoles DROP FOREIGN KEY FK_B135227271F7E88B');
        $this->addSql('ALTER TABLE dates_evenements DROP FOREIGN KEY FK_A00387DD71F7E88B');
        $this->addSql('ALTER TABLE event_galops DROP FOREIGN KEY FK_E6B02A3871F7E88B');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6AFD02F13');
        $this->addSql('ALTER TABLE participe DROP FOREIGN KEY FK_9FFA8D471F7E88B');
        $this->addSql('ALTER TABLE utilisateur_event DROP FOREIGN KEY FK_FD6B527971F7E88B');
        $this->addSql('ALTER TABLE utilisateur_moyen_paiement_event DROP FOREIGN KEY FK_F823F1C71F7E88B');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2CFD02F13');
        $this->addSql('ALTER TABLE event_galops DROP FOREIGN KEY FK_E6B02A386EA63067');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B34A6A299');
        $this->addSql('ALTER TABLE participe DROP FOREIGN KEY FK_9FFA8D4FB88E14F');
        $this->addSql('ALTER TABLE utilisateur_event DROP FOREIGN KEY FK_FD6B5279FB88E14F');
        $this->addSql('ALTER TABLE utilisateur_creneaux_benevoles DROP FOREIGN KEY FK_7D1BEE82FB88E14F');
        $this->addSql('ALTER TABLE utilisateur_moyen_paiement_event DROP FOREIGN KEY FK_F823F1CFB88E14F');
        $this->addSql('DROP TABLE attribut_moyen_paiements');
        $this->addSql('DROP TABLE creneaux_benevoles');
        $this->addSql('DROP TABLE dates_evenements');
        $this->addSql('DROP TABLE description');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_galops');
        $this->addSql('DROP TABLE files_pdf');
        $this->addSql('DROP TABLE galops');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE participe');
        $this->addSql('DROP TABLE utilisateur_event');
        $this->addSql('DROP TABLE utilisateur_creneaux_benevoles');
        $this->addSql('DROP TABLE utilisateur_moyen_paiement_event');
        $this->addSql('DROP TABLE video');
    }
}
