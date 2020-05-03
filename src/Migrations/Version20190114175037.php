<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190114175037 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('INSERT INTO competition_class (`id`, `name`) VALUES (1, "I")');
        $this->addSql('INSERT INTO competition_class (`id`, `name`) VALUES (2, "II")');
        $this->addSql('INSERT INTO competition_class (`id`, `name`) VALUES (3, "III")');
        $this->addSql('INSERT INTO competition_class (`id`, `name`) VALUES (4, "IV")');
        $this->addSql('INSERT INTO competition_class (`id`, `name`) VALUES (5, "V")');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DELETE FROM competition_class WHERE `id` IN (1, 2, 3, 4, 5)');
    }
}
