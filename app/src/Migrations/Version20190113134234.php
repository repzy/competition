<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190113134234 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE competition ADD region_id INT DEFAULT NULL, DROP class, DROP region, DROP attachments');
        $this->addSql('ALTER TABLE competition ADD CONSTRAINT FK_B50A2CB198260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE competition_attachment DROP FOREIGN KEY FK_DA483983DBD82A81');
        $this->addSql('DROP INDEX IDX_DA483983DBD82A81 ON competition_attachment');
        $this->addSql('ALTER TABLE competition_attachment DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE competition_attachment CHANGE attachemnt_id attachment_id INT NOT NULL');
        $this->addSql('ALTER TABLE competition_attachment ADD CONSTRAINT FK_DA483983464E68B FOREIGN KEY (attachment_id) REFERENCES attachment (id)');
        $this->addSql('CREATE INDEX IDX_DA483983464E68B ON competition_attachment (attachment_id)');
        $this->addSql('ALTER TABLE competition_attachment ADD PRIMARY KEY (competition_id, attachment_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE competition DROP FOREIGN KEY FK_B50A2CB198260155');
        $this->addSql('DROP INDEX UNIQ_B50A2CB198260155 ON competition');
        $this->addSql('ALTER TABLE competition ADD class INT NOT NULL, ADD region VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD attachments VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP region_id, CHANGE date date DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE competition_attachment DROP FOREIGN KEY FK_DA483983464E68B');
        $this->addSql('DROP INDEX IDX_DA483983464E68B ON competition_attachment');
        $this->addSql('ALTER TABLE competition_attachment DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE competition_attachment CHANGE attachment_id attachemnt_id INT NOT NULL');
        $this->addSql('ALTER TABLE competition_attachment ADD CONSTRAINT FK_DA483983DBD82A81 FOREIGN KEY (attachemnt_id) REFERENCES attachment (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_DA483983DBD82A81 ON competition_attachment (attachemnt_id)');
        $this->addSql('ALTER TABLE competition_attachment ADD PRIMARY KEY (competition_id, attachemnt_id)');
    }
}
