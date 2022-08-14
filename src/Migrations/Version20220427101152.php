<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220427101152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_landing_page_translation DROP FOREIGN KEY FK_90BE53BB2C2AC5D3');
        $this->addSql('DROP TABLE app_landing_page');
        $this->addSql('DROP TABLE app_landing_page_translation');
        $this->addSql('ALTER TABLE app_local_business DROP FOREIGN KEY FK_44BDD65E537A1329');
        $this->addSql('DROP INDEX IDX_44BDD65E537A1329 ON app_local_business');
        $this->addSql('ALTER TABLE app_local_business DROP message_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_landing_page (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, code VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, isEnabled TINYINT(1) DEFAULT \'1\' NOT NULL, isIndexed TINYINT(1) DEFAULT \'1\' NOT NULL, isLocked TINYINT(1) NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, primaryImage_id INT DEFAULT NULL, INDEX IDX_4529D21812469DE2 (category_id), INDEX IDX_4529D2187B02E272 (primaryImage_id), UNIQUE INDEX UNIQ_4529D21877153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE app_landing_page_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, component LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, slug VARCHAR(128) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, meta_title VARCHAR(70) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, meta_description VARCHAR(160) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, mainEntityOfPage VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, headline VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, alternativeHeadline VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, pushForward VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, text LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, textResume LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, datePublished DATE DEFAULT NULL, expire DATE DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, locale VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX app_landing_page_translation_uniq_trans (translatable_id, locale), INDEX IDX_90BE53BB2C2AC5D3 (translatable_id), UNIQUE INDEX UNIQ_90BE53BB989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE app_landing_page ADD CONSTRAINT FK_4529D21812469DE2 FOREIGN KEY (category_id) REFERENCES app_category (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE app_landing_page ADD CONSTRAINT FK_4529D2187B02E272 FOREIGN KEY (primaryImage_id) REFERENCES app_media_object (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('ALTER TABLE app_landing_page_translation ADD CONSTRAINT FK_90BE53BB2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES app_landing_page (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_local_business ADD message_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app_local_business ADD CONSTRAINT FK_44BDD65E537A1329 FOREIGN KEY (message_id) REFERENCES app_message (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_44BDD65E537A1329 ON app_local_business (message_id)');
    }
}
