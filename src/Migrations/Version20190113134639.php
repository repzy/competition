<?php
declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190113134639 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS competition_class (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;'
        );
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;'
        );
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, resetting_code VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;'
        );
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS distance (id INT AUTO_INCREMENT NOT NULL, competition_id INT NOT NULL, class_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, date DATE NOT NULL, INDEX IDX_1C929A817B39D312 (competition_id), INDEX IDX_1C929A81EA000B10 (class_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;'
        );
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS distance_attachment (distance_id INT NOT NULL, attachment_id INT NOT NULL, INDEX IDX_B585956013192463 (distance_id), INDEX IDX_B5859560464E68B (attachment_id), PRIMARY KEY(distance_id, attachment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;'
        );
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS competition (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, author_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, date DATE NOT NULL, INDEX IDX_B50A2CB198260155 (region_id), INDEX IDX_B50A2CB1F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;'
        );
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS competition_attachment (competition_id INT NOT NULL, attachment_id INT NOT NULL, INDEX IDX_DA4839837B39D312 (competition_id), INDEX IDX_DA483983464E68B (attachment_id), PRIMARY KEY(competition_id, attachment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;'
        );
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS comment (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, distance_id INT NOT NULL, user_id INT NOT NULL, text LONGTEXT NOT NULL, date DATE NOT NULL, INDEX IDX_9474526C727ACA70 (parent_id), INDEX IDX_9474526C13192463 (distance_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;'
        );
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS attachment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, extension VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;'
        );
        $this->addSql(
            'ALTER TABLE distance ADD CONSTRAINT FK_1C929A817B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id);'
        );
        $this->addSql(
            'ALTER TABLE distance ADD CONSTRAINT FK_1C929A81EA000B10 FOREIGN KEY (class_id) REFERENCES competition_class (id);'
        );
        $this->addSql(
            'ALTER TABLE distance_attachment ADD CONSTRAINT FK_B585956013192463 FOREIGN KEY (distance_id) REFERENCES distance (id);'
        );
        $this->addSql(
            'ALTER TABLE distance_attachment ADD CONSTRAINT FK_B5859560464E68B FOREIGN KEY (attachment_id) REFERENCES attachment (id);'
        );
        $this->addSql(
            'ALTER TABLE competition ADD CONSTRAINT FK_B50A2CB198260155 FOREIGN KEY (region_id) REFERENCES region (id);'
        );
        $this->addSql(
            'ALTER TABLE competition ADD CONSTRAINT FK_B50A2CB1F675F31B FOREIGN KEY (author_id) REFERENCES user (id);'
        );
        $this->addSql(
            'ALTER TABLE competition_attachment ADD CONSTRAINT FK_DA4839837B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id);'
        );
        $this->addSql(
            'ALTER TABLE competition_attachment ADD CONSTRAINT FK_DA483983464E68B FOREIGN KEY (attachment_id) REFERENCES attachment (id);'
        );
        $this->addSql(
            'ALTER TABLE comment ADD CONSTRAINT FK_9474526C727ACA70 FOREIGN KEY (parent_id) REFERENCES comment (id);'
        );
        $this->addSql(
            'ALTER TABLE comment ADD CONSTRAINT FK_9474526C13192463 FOREIGN KEY (distance_id) REFERENCES distance (id);'
        );
        $this->addSql(
            'ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id);'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE competition_class;');
        $this->addSql('DROP TABLE region;');
        $this->addSql('DROP TABLE user;');
        $this->addSql('DROP TABLE distance;');
        $this->addSql('DROP TABLE distance_attachment;');
        $this->addSql('DROP TABLE competition;');
        $this->addSql('DROP TABLE competition_attachment;');
        $this->addSql('DROP TABLE comment;');
        $this->addSql('DROP TABLE attachment;');
    }
}
