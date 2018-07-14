<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180612194212 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE bookie_team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, abbreviation VARCHAR(6) NOT NULL, UNIQUE INDEX UNIQ_547885B65E237E06 (name), UNIQUE INDEX UNIQ_547885B6BCF3411D (abbreviation), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookie_bet (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, match_id INT NOT NULL, home_score SMALLINT DEFAULT NULL, away_score SMALLINT DEFAULT NULL, points SMALLINT DEFAULT NULL, INDEX IDX_DCCED494A76ED395 (user_id), INDEX IDX_DCCED4942ABEACD6 (match_id), UNIQUE INDEX user_match_idx (user_id, match_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookie_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(30) NOT NULL, password VARCHAR(64) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', UNIQUE INDEX UNIQ_1D0BF5E0F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookie_match (id INT AUTO_INCREMENT NOT NULL, home_team_id INT NOT NULL, away_team_id INT NOT NULL, status VARCHAR(255) NOT NULL, kick_off DATETIME DEFAULT NULL, home_score SMALLINT DEFAULT NULL, away_score SMALLINT DEFAULT NULL, INDEX IDX_D5C1466A9C4C13F6 (home_team_id), INDEX IDX_D5C1466A45185D02 (away_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bookie_bet ADD CONSTRAINT FK_DCCED494A76ED395 FOREIGN KEY (user_id) REFERENCES bookie_user (id)');
        $this->addSql('ALTER TABLE bookie_bet ADD CONSTRAINT FK_DCCED4942ABEACD6 FOREIGN KEY (match_id) REFERENCES bookie_match (id)');
        $this->addSql('ALTER TABLE bookie_match ADD CONSTRAINT FK_D5C1466A9C4C13F6 FOREIGN KEY (home_team_id) REFERENCES bookie_team (id)');
        $this->addSql('ALTER TABLE bookie_match ADD CONSTRAINT FK_D5C1466A45185D02 FOREIGN KEY (away_team_id) REFERENCES bookie_team (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bookie_match DROP FOREIGN KEY FK_D5C1466A9C4C13F6');
        $this->addSql('ALTER TABLE bookie_match DROP FOREIGN KEY FK_D5C1466A45185D02');
        $this->addSql('ALTER TABLE bookie_bet DROP FOREIGN KEY FK_DCCED494A76ED395');
        $this->addSql('ALTER TABLE bookie_bet DROP FOREIGN KEY FK_DCCED4942ABEACD6');
        $this->addSql('DROP TABLE bookie_team');
        $this->addSql('DROP TABLE bookie_bet');
        $this->addSql('DROP TABLE bookie_user');
        $this->addSql('DROP TABLE bookie_match');
    }
}
