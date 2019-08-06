<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190805131356 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE images CHANGE evenement_id evenement_id INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateur_moyen_paiement_event DROP FOREIGN KEY FK_F823F1C1E969C5');
        $this->addSql('ALTER TABLE utilisateur_moyen_paiement_event DROP FOREIGN KEY FK_F823F1C9D6A1065');
        $this->addSql('ALTER TABLE utilisateur_moyen_paiement_event DROP FOREIGN KEY FK_F823F1CC7B9BAA5');
        $this->addSql('DROP INDEX IDX_F823F1CC7B9BAA5 ON utilisateur_moyen_paiement_event');
        $this->addSql('DROP INDEX IDX_F823F1C9D6A1065 ON utilisateur_moyen_paiement_event');
        $this->addSql('DROP INDEX IDX_F823F1C1E969C5 ON utilisateur_moyen_paiement_event');
        $this->addSql('ALTER TABLE utilisateur_moyen_paiement_event DROP attribut_moyen_paiements_id, DROP utilisateurs_id, DROP events_id');
        $this->addSql('ALTER TABLE video CHANGE evenement_id evenement_id INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE images CHANGE evenement_id evenement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur_moyen_paiement_event ADD attribut_moyen_paiements_id INT DEFAULT NULL, ADD utilisateurs_id INT DEFAULT NULL, ADD events_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur_moyen_paiement_event ADD CONSTRAINT FK_F823F1C1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur_moyen_paiement_event ADD CONSTRAINT FK_F823F1C9D6A1065 FOREIGN KEY (events_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE utilisateur_moyen_paiement_event ADD CONSTRAINT FK_F823F1CC7B9BAA5 FOREIGN KEY (attribut_moyen_paiements_id) REFERENCES attribut_moyen_paiements (id)');
        $this->addSql('CREATE INDEX IDX_F823F1CC7B9BAA5 ON utilisateur_moyen_paiement_event (attribut_moyen_paiements_id)');
        $this->addSql('CREATE INDEX IDX_F823F1C9D6A1065 ON utilisateur_moyen_paiement_event (events_id)');
        $this->addSql('CREATE INDEX IDX_F823F1C1E969C5 ON utilisateur_moyen_paiement_event (utilisateurs_id)');
        $this->addSql('ALTER TABLE video CHANGE evenement_id evenement_id INT DEFAULT NULL');
    }
}
