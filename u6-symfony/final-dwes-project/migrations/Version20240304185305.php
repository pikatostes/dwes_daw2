<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240304185305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_880E0D76F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playlists (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_5E06116F9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playlists_songs (playlists_id INT NOT NULL, songs_id INT NOT NULL, INDEX IDX_D7BF02DC9F70CF56 (playlists_id), INDEX IDX_D7BF02DCC365A331 (songs_id), PRIMARY KEY(playlists_id, songs_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE songs (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, cover VARCHAR(255) NOT NULL, audio VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE playlists ADD CONSTRAINT FK_5E06116F9D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE playlists_songs ADD CONSTRAINT FK_D7BF02DC9F70CF56 FOREIGN KEY (playlists_id) REFERENCES playlists (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE playlists_songs ADD CONSTRAINT FK_D7BF02DCC365A331 FOREIGN KEY (songs_id) REFERENCES songs (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlists DROP FOREIGN KEY FK_5E06116F9D86650F');
        $this->addSql('ALTER TABLE playlists_songs DROP FOREIGN KEY FK_D7BF02DC9F70CF56');
        $this->addSql('ALTER TABLE playlists_songs DROP FOREIGN KEY FK_D7BF02DCC365A331');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE playlists');
        $this->addSql('DROP TABLE playlists_songs');
        $this->addSql('DROP TABLE songs');
        $this->addSql('DROP TABLE users');
    }
}
