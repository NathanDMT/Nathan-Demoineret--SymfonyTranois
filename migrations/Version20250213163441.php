<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213163441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gallery (id_gallery INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, gallery_name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, is_published TINYINT(1) NOT NULL, INDEX IDX_472B783AA76ED395 (user_id), PRIMARY KEY(id_gallery)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id_photo INT AUTO_INCREMENT NOT NULL, gallery_id INT DEFAULT NULL, file_name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, date_upload DATETIME NOT NULL, file_size INT NOT NULL, publication_order INT DEFAULT NULL, INDEX IDX_14B784184E7AF8F (gallery_id), PRIMARY KEY(id_photo)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, age INT NOT NULL, password VARCHAR(255) NOT NULL, is_blocked TINYINT(1) NOT NULL, is_admin TINYINT(1) NOT NULL, roles JSON NOT NULL, failed_attempts INT NOT NULL, creation_date DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gallery ADD CONSTRAINT FK_472B783AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B784184E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id_gallery) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gallery DROP FOREIGN KEY FK_472B783AA76ED395');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B784184E7AF8F');
        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE `user`');
    }
}
