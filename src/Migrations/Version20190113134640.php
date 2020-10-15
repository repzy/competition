<?php declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190113134640 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('INSERT INTO region (`name`) VALUES ("м. Київ")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Вінницька область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Волинська область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Дніпропетровська область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Донецька область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Житомирська область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Закарпатська область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Запорізька область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Івано-Франківська область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Київська область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Кіровоградська область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Луганська область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Львівська область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Миколаївська область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Одеська область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Полтавська область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Рівненська область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Сумська область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Тернопільська область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Харківська область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Херсонська область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Хмельницька область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Черкаська область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Чернівецька область")');
        $this->addSql('INSERT INTO region (`name`) VALUES ("Чернігівська область")');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DELETE FROM region WHERE `name` IN (
            "Вінницька область",
            "Волинська область",
            "Дніпропетровська область",
            "Донецька область",
            "Житомирська область",
            "Закарпатська область",
            "Запорізька область",
            "Івано-Франківська область",
            "Київська область",
            "Кіровоградська область",
            "Луганська область",
            "Львівська область",
            "Миколаївська область",
            "Одеська область",
            "Полтавська область",
            "Рівненська область",
            "Сумська область",
            "Тернопільська область",
            "Харківська область",
            "Херсонська область",
            "Хмельницька область",
            "Черкаська область",
            "Чернівецька область",
            "Чернігівська область")');
    }
}
