<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190511190153 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE configuration (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, value LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_A5E2A5D75E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formbuilder (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, formbuilder LONGTEXT NOT NULL, enable TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_2DFA9C645E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE history (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', refuser_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, enable TINYINT(1) DEFAULT \'1\' NOT NULL, slug VARCHAR(255) DEFAULT NULL, file VARCHAR(255) DEFAULT NULL, end TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_27BA704B2B445CEF (refuser_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chapitre (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', refhistory_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, position INT NOT NULL, enable TINYINT(1) DEFAULT \'1\' NOT NULL, status VARCHAR(255) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_8C62B02520C3240A (refhistory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', refuser_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', refcategory_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, file VARCHAR(255) DEFAULT NULL, content LONGTEXT NOT NULL, slug VARCHAR(255) DEFAULT NULL, enable TINYINT(1) DEFAULT \'1\' NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_5A8A6C8D5E237E06 (name), INDEX IDX_5A8A6C8D2B445CEF (refuser_id), INDEX IDX_5A8A6C8D77C88284 (refcategory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_tags (post_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', tags_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_A6E9F32D4B89032C (post_id), INDEX IDX_A6E9F32D8D7B4FB4 (tags_id), PRIMARY KEY(post_id, tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_64C19C15E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_6FBC94265E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', username VARCHAR(180) NOT NULL, email VARCHAR(180) DEFAULT \'1\' NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, api_key VARCHAR(64) DEFAULT NULL, enable TINYINT(1) DEFAULT \'1\' NOT NULL, avatar VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649C912ED9D (api_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth_connect_user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', refuser_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, data LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', identity VARCHAR(255) DEFAULT NULL, INDEX IDX_5990C96F2B445CEF (refuser_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704B2B445CEF FOREIGN KEY (refuser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT FK_8C62B02520C3240A FOREIGN KEY (refhistory_id) REFERENCES history (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D2B445CEF FOREIGN KEY (refuser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D77C88284 FOREIGN KEY (refcategory_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE post_tags ADD CONSTRAINT FK_A6E9F32D4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_tags ADD CONSTRAINT FK_A6E9F32D8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oauth_connect_user ADD CONSTRAINT FK_5990C96F2B445CEF FOREIGN KEY (refuser_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY FK_8C62B02520C3240A');
        $this->addSql('ALTER TABLE post_tags DROP FOREIGN KEY FK_A6E9F32D4B89032C');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D77C88284');
        $this->addSql('ALTER TABLE post_tags DROP FOREIGN KEY FK_A6E9F32D8D7B4FB4');
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704B2B445CEF');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D2B445CEF');
        $this->addSql('ALTER TABLE oauth_connect_user DROP FOREIGN KEY FK_5990C96F2B445CEF');
        $this->addSql('DROP TABLE configuration');
        $this->addSql('DROP TABLE formbuilder');
        $this->addSql('DROP TABLE history');
        $this->addSql('DROP TABLE chapitre');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_tags');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE oauth_connect_user');
    }
}
