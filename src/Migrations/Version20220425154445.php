<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220425154445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_address (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, address VARCHAR(255) NOT NULL, postcode VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_article (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, video_id INT DEFAULT NULL, datePublished DATE DEFAULT NULL, lastReview DATE DEFAULT NULL, isEnabled TINYINT(1) DEFAULT \'1\' NOT NULL, isIndexed TINYINT(1) DEFAULT \'1\' NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, primaryImage_id INT DEFAULT NULL, INDEX IDX_EF678E2B7B02E272 (primaryImage_id), INDEX IDX_EF678E2B12469DE2 (category_id), INDEX IDX_EF678E2B29C1004E (video_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_article_category (article_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_C79A0C0B7294869C (article_id), INDEX IDX_C79A0C0B12469DE2 (category_id), PRIMARY KEY(article_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_article_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, slug VARCHAR(128) NOT NULL, articleBody LONGTEXT DEFAULT NULL, articleResume LONGTEXT DEFAULT NULL, component LONGTEXT DEFAULT NULL, meta_title VARCHAR(70) DEFAULT NULL, meta_description VARCHAR(160) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, mainEntityOfPage VARCHAR(255) DEFAULT NULL, headline VARCHAR(255) DEFAULT NULL, alternativeHeadline VARCHAR(255) DEFAULT NULL, pushForward VARCHAR(255) DEFAULT NULL, text LONGTEXT DEFAULT NULL, textResume LONGTEXT DEFAULT NULL, datePublished DATE DEFAULT NULL, expire DATE DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, locale VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7054C7CD989D9B62 (slug), INDEX IDX_7054C7CD2C2AC5D3 (translatable_id), UNIQUE INDEX app_article_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_category (id INT AUTO_INCREMENT NOT NULL, icon_id INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, isEnabled TINYINT(1) DEFAULT \'1\' NOT NULL, isIndexed TINYINT(1) DEFAULT \'1\' NOT NULL, isLocked TINYINT(1) NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, primaryImage_id INT DEFAULT NULL, INDEX IDX_ECC796C7B02E272 (primaryImage_id), INDEX IDX_ECC796C54B9D732 (icon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_category_localbusiness (category_id INT NOT NULL, localbusiness_id INT NOT NULL, INDEX IDX_D28F1C1912469DE2 (category_id), INDEX IDX_D28F1C19A9F51E7F (localbusiness_id), PRIMARY KEY(category_id, localbusiness_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_category_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, slug VARCHAR(128) NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, meta_title VARCHAR(70) DEFAULT NULL, meta_description VARCHAR(160) DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, locale VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E8A28FD3989D9B62 (slug), INDEX IDX_E8A28FD32C2AC5D3 (translatable_id), UNIQUE INDEX app_category_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_cms_component (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, is_enabled TINYINT(1) NOT NULL, code VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_10E5FC9677153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_cms_style (id INT AUTO_INCREMENT NOT NULL, cms_component_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, is_enabled TINYINT(1) NOT NULL, code VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B5A17C7377153098 (code), INDEX IDX_B5A17C7363885885 (cms_component_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_cms_template (id INT AUTO_INCREMENT NOT NULL, cms_component_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, is_enabled TINYINT(1) NOT NULL, code VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3F00C9FF77153098 (code), INDEX IDX_3F00C9FF63885885 (cms_component_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_landing_page (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, isEnabled TINYINT(1) DEFAULT \'1\' NOT NULL, isIndexed TINYINT(1) DEFAULT \'1\' NOT NULL, isLocked TINYINT(1) NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, primaryImage_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_4529D21877153098 (code), INDEX IDX_4529D2187B02E272 (primaryImage_id), INDEX IDX_4529D21812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_landing_page_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, component LONGTEXT DEFAULT NULL, slug VARCHAR(128) NOT NULL, meta_title VARCHAR(70) DEFAULT NULL, meta_description VARCHAR(160) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, mainEntityOfPage VARCHAR(255) DEFAULT NULL, headline VARCHAR(255) DEFAULT NULL, alternativeHeadline VARCHAR(255) DEFAULT NULL, pushForward VARCHAR(255) DEFAULT NULL, text LONGTEXT DEFAULT NULL, textResume LONGTEXT DEFAULT NULL, datePublished DATE DEFAULT NULL, expire DATE DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, locale VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_90BE53BB989D9B62 (slug), INDEX IDX_90BE53BB2C2AC5D3 (translatable_id), UNIQUE INDEX app_landing_page_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_local_business (id INT AUTO_INCREMENT NOT NULL, organization_id INT NOT NULL, products_id INT DEFAULT NULL, message_id INT DEFAULT NULL, slug VARCHAR(128) NOT NULL, gmap VARCHAR(255) DEFAULT NULL, isEnabled TINYINT(1) DEFAULT \'1\' NOT NULL, isIndexed TINYINT(1) DEFAULT \'1\' NOT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, mainEntityOfPage VARCHAR(255) DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, UNIQUE INDEX UNIQ_44BDD65E989D9B62 (slug), INDEX IDX_44BDD65E32C8A3DE (organization_id), INDEX IDX_44BDD65E6C8A81A9 (products_id), INDEX IDX_44BDD65E537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_media_object (id INT AUTO_INCREMENT NOT NULL, organization_id INT DEFAULT NULL, message_id INT DEFAULT NULL, slug VARCHAR(160) NOT NULL, contentSize INT DEFAULT NULL, encodingFormat VARCHAR(255) DEFAULT NULL, caption VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) DEFAULT NULL, originalFilename VARCHAR(255) DEFAULT NULL, dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', tmpFile VARCHAR(255) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, isActived TINYINT(1) DEFAULT \'1\' NOT NULL, alt VARCHAR(255) DEFAULT NULL, icon VARCHAR(255) DEFAULT NULL, isEnabled TINYINT(1) DEFAULT \'1\' NOT NULL, isIndexed TINYINT(1) DEFAULT \'1\' NOT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, mainEntityOfPage VARCHAR(255) DEFAULT NULL, headline VARCHAR(255) DEFAULT NULL, alternativeHeadline VARCHAR(255) DEFAULT NULL, pushForward VARCHAR(255) DEFAULT NULL, text LONGTEXT DEFAULT NULL, textResume LONGTEXT DEFAULT NULL, datePublished DATE DEFAULT NULL, expire DATE DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, UNIQUE INDEX UNIQ_D65A2BB3989D9B62 (slug), INDEX IDX_D65A2BB332C8A3DE (organization_id), INDEX IDX_D65A2BB3537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_message (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, dateSent DATETIME DEFAULT NULL, subject VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, origin VARCHAR(255) DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, localBusiness_id INT DEFAULT NULL, INDEX IDX_5BE0B032F624B39D (sender_id), INDEX IDX_5BE0B0322F6991A8 (localBusiness_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_opening_hours_specification (id INT AUTO_INCREMENT NOT NULL, closes VARCHAR(255) DEFAULT NULL, dayOfWeek VARCHAR(255) DEFAULT NULL, opens VARCHAR(255) DEFAULT NULL, validFrom VARCHAR(255) DEFAULT NULL, validTrough VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, mainEntityOfPage VARCHAR(255) DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, localBusiness_id INT DEFAULT NULL, INDEX IDX_D5865C882F6991A8 (localBusiness_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_organization (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, slug VARCHAR(128) NOT NULL, name VARCHAR(255) NOT NULL, legal_name VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL COMMENT \'Phone\', email VARCHAR(255) DEFAULT NULL COMMENT \'Email\', foundingDate DATE DEFAULT NULL, number_of_employees INT DEFAULT NULL, numberOfProjects SMALLINT DEFAULT NULL, mobile_phone VARCHAR(255) DEFAULT NULL COMMENT \'Mobile Phone\', icon VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, isEnabled TINYINT(1) DEFAULT \'1\' NOT NULL, isIndexed TINYINT(1) DEFAULT \'1\' NOT NULL, description LONGTEXT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, mainEntityOfPage VARCHAR(255) DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, primaryImage_id INT DEFAULT NULL, secondaryImage_id INT DEFAULT NULL, iconMedia_id INT DEFAULT NULL, INDEX IDX_36079FD7B02E272 (primaryImage_id), INDEX IDX_36079FD75855086 (secondaryImage_id), INDEX IDX_36079FD12469DE2 (category_id), INDEX IDX_36079FD727ACA70 (parent_id), INDEX IDX_36079FDC59E054E (iconMedia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_organization_addresse (organisation_id INT NOT NULL, address_id INT NOT NULL, INDEX IDX_822C52DE9E6B1585 (organisation_id), INDEX IDX_822C52DEF5B7AF75 (address_id), PRIMARY KEY(organisation_id, address_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_social_link_organization (organization_source INT NOT NULL, organization_target INT NOT NULL, INDEX IDX_F265EF05A04AF86 (organization_source), INDEX IDX_F265EF043E1FF09 (organization_target), PRIMARY KEY(organization_source, organization_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_person (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) DEFAULT NULL COMMENT \'Firstname\', lastname VARCHAR(255) NOT NULL COMMENT \'Lastname\', birthday DATE DEFAULT NULL COMMENT \'Birthday\', place_of_birth VARCHAR(255) DEFAULT NULL COMMENT \'Place of birth\', phone VARCHAR(255) DEFAULT NULL COMMENT \'Phone\', email VARCHAR(255) DEFAULT NULL COMMENT \'Email\', name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, mainEntityOfPage VARCHAR(255) DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_place (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, mainEntityOfPage VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_property_value (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, slug VARCHAR(128) NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) DEFAULT NULL, valueReference LONGTEXT DEFAULT NULL, valueMax INT DEFAULT NULL, valueMin INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, mainEntityOfPage VARCHAR(255) DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, webPage_id INT DEFAULT NULL, localBusiness_id INT DEFAULT NULL, INDEX IDX_49E0BC72DB210BC (webPage_id), INDEX IDX_49E0BC77294869C (article_id), INDEX IDX_49E0BC72F6991A8 (localBusiness_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_search_action (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, mainEntityOfPage VARCHAR(255) NOT NULL, query JSON DEFAULT NULL, orderByDate VARCHAR(255) DEFAULT NULL, limitResult INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_category_searchaction (searchaction_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_3B4A978AA3A51EE0 (searchaction_id), INDEX IDX_3B4A978A12469DE2 (category_id), PRIMARY KEY(searchaction_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_service (id INT AUTO_INCREMENT NOT NULL, icon_id INT DEFAULT NULL, slug VARCHAR(128) NOT NULL, isEnabled TINYINT(1) DEFAULT \'1\' NOT NULL, isIndexed TINYINT(1) DEFAULT \'1\' NOT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, mainEntityOfPage VARCHAR(255) DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, UNIQUE INDEX UNIQ_CC01A9F989D9B62 (slug), INDEX IDX_CC01A9F54B9D732 (icon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_service_localbusiness (service_id INT NOT NULL, localbusiness_id INT NOT NULL, INDEX IDX_29AD27B5ED5CA9E6 (service_id), INDEX IDX_29AD27B5A9F51E7F (localbusiness_id), PRIMARY KEY(service_id, localbusiness_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_special_announcement (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, datePosted DATE DEFAULT NULL, isEnabled TINYINT(1) DEFAULT \'1\' NOT NULL, isIndexed TINYINT(1) DEFAULT \'1\' NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, primaryImage_id INT DEFAULT NULL, INDEX IDX_7E2FFFC27B02E272 (primaryImage_id), INDEX IDX_7E2FFFC212469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_special_announcement_category (specialannouncement_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_902B544DADEF5AF8 (specialannouncement_id), INDEX IDX_902B544D12469DE2 (category_id), PRIMARY KEY(specialannouncement_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_special_announcement_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, component LONGTEXT DEFAULT NULL, slug VARCHAR(128) NOT NULL, meta_title VARCHAR(70) DEFAULT NULL, meta_description VARCHAR(160) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, mainEntityOfPage VARCHAR(255) DEFAULT NULL, headline VARCHAR(255) DEFAULT NULL, alternativeHeadline VARCHAR(255) DEFAULT NULL, pushForward VARCHAR(255) DEFAULT NULL, text LONGTEXT DEFAULT NULL, textResume LONGTEXT DEFAULT NULL, datePublished DATE DEFAULT NULL, expire DATE DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, locale VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_CA4762AE989D9B62 (slug), INDEX IDX_CA4762AE2C2AC5D3 (translatable_id), UNIQUE INDEX app_special_announcement_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_user_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, code VARCHAR(255) NOT NULL, enabled TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_D91914E177153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_web_page (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, isEnabled TINYINT(1) DEFAULT \'1\' NOT NULL, isIndexed TINYINT(1) DEFAULT \'1\' NOT NULL, isLocked TINYINT(1) NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, primaryImage_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_D888DB7877153098 (code), INDEX IDX_D888DB787B02E272 (primaryImage_id), INDEX IDX_D888DB7812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_web_page_category (webpage_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_7D146DF1E20F2920 (webpage_id), INDEX IDX_7D146DF112469DE2 (category_id), PRIMARY KEY(webpage_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_web_page_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, component LONGTEXT DEFAULT NULL, slug VARCHAR(128) NOT NULL, meta_title VARCHAR(70) DEFAULT NULL, meta_description VARCHAR(160) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, mainEntityOfPage VARCHAR(255) DEFAULT NULL, headline VARCHAR(255) DEFAULT NULL, alternativeHeadline VARCHAR(255) DEFAULT NULL, pushForward VARCHAR(255) DEFAULT NULL, text LONGTEXT DEFAULT NULL, textResume LONGTEXT DEFAULT NULL, datePublished DATE DEFAULT NULL, expire DATE DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, locale VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_BEE1C5B0989D9B62 (slug), INDEX IDX_BEE1C5B02C2AC5D3 (translatable_id), UNIQUE INDEX app_web_page_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_article ADD CONSTRAINT FK_EF678E2B7B02E272 FOREIGN KEY (primaryImage_id) REFERENCES app_media_object (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE app_article ADD CONSTRAINT FK_EF678E2B12469DE2 FOREIGN KEY (category_id) REFERENCES app_category (id)');
        $this->addSql('ALTER TABLE app_article ADD CONSTRAINT FK_EF678E2B29C1004E FOREIGN KEY (video_id) REFERENCES app_media_object (id)');
        $this->addSql('ALTER TABLE app_article_category ADD CONSTRAINT FK_C79A0C0B7294869C FOREIGN KEY (article_id) REFERENCES app_article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_article_category ADD CONSTRAINT FK_C79A0C0B12469DE2 FOREIGN KEY (category_id) REFERENCES app_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_article_translation ADD CONSTRAINT FK_7054C7CD2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES app_article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_category ADD CONSTRAINT FK_ECC796C7B02E272 FOREIGN KEY (primaryImage_id) REFERENCES app_media_object (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE app_category ADD CONSTRAINT FK_ECC796C54B9D732 FOREIGN KEY (icon_id) REFERENCES app_media_object (id)');
        $this->addSql('ALTER TABLE app_category_localbusiness ADD CONSTRAINT FK_D28F1C1912469DE2 FOREIGN KEY (category_id) REFERENCES app_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_category_localbusiness ADD CONSTRAINT FK_D28F1C19A9F51E7F FOREIGN KEY (localbusiness_id) REFERENCES app_local_business (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_category_translation ADD CONSTRAINT FK_E8A28FD32C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES app_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_cms_style ADD CONSTRAINT FK_B5A17C7363885885 FOREIGN KEY (cms_component_id) REFERENCES app_cms_component (id)');
        $this->addSql('ALTER TABLE app_cms_template ADD CONSTRAINT FK_3F00C9FF63885885 FOREIGN KEY (cms_component_id) REFERENCES app_cms_component (id)');
        $this->addSql('ALTER TABLE app_landing_page ADD CONSTRAINT FK_4529D2187B02E272 FOREIGN KEY (primaryImage_id) REFERENCES app_media_object (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE app_landing_page ADD CONSTRAINT FK_4529D21812469DE2 FOREIGN KEY (category_id) REFERENCES app_category (id)');
        $this->addSql('ALTER TABLE app_landing_page_translation ADD CONSTRAINT FK_90BE53BB2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES app_landing_page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_local_business ADD CONSTRAINT FK_44BDD65E32C8A3DE FOREIGN KEY (organization_id) REFERENCES app_organization (id)');
        $this->addSql('ALTER TABLE app_local_business ADD CONSTRAINT FK_44BDD65E6C8A81A9 FOREIGN KEY (products_id) REFERENCES sylius_product (id)');
        $this->addSql('ALTER TABLE app_local_business ADD CONSTRAINT FK_44BDD65E537A1329 FOREIGN KEY (message_id) REFERENCES app_message (id)');
        $this->addSql('ALTER TABLE app_media_object ADD CONSTRAINT FK_D65A2BB332C8A3DE FOREIGN KEY (organization_id) REFERENCES app_organization (id)');
        $this->addSql('ALTER TABLE app_media_object ADD CONSTRAINT FK_D65A2BB3537A1329 FOREIGN KEY (message_id) REFERENCES app_message (id)');
        $this->addSql('ALTER TABLE app_message ADD CONSTRAINT FK_5BE0B032F624B39D FOREIGN KEY (sender_id) REFERENCES app_person (id)');
        $this->addSql('ALTER TABLE app_message ADD CONSTRAINT FK_5BE0B0322F6991A8 FOREIGN KEY (localBusiness_id) REFERENCES app_local_business (id)');
        $this->addSql('ALTER TABLE app_opening_hours_specification ADD CONSTRAINT FK_D5865C882F6991A8 FOREIGN KEY (localBusiness_id) REFERENCES app_local_business (id)');
        $this->addSql('ALTER TABLE app_organization ADD CONSTRAINT FK_36079FD7B02E272 FOREIGN KEY (primaryImage_id) REFERENCES app_media_object (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE app_organization ADD CONSTRAINT FK_36079FD75855086 FOREIGN KEY (secondaryImage_id) REFERENCES app_media_object (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE app_organization ADD CONSTRAINT FK_36079FD12469DE2 FOREIGN KEY (category_id) REFERENCES app_category (id)');
        $this->addSql('ALTER TABLE app_organization ADD CONSTRAINT FK_36079FD727ACA70 FOREIGN KEY (parent_id) REFERENCES app_organization (id)');
        $this->addSql('ALTER TABLE app_organization ADD CONSTRAINT FK_36079FDC59E054E FOREIGN KEY (iconMedia_id) REFERENCES app_media_object (id)');
        $this->addSql('ALTER TABLE app_organization_addresse ADD CONSTRAINT FK_822C52DE9E6B1585 FOREIGN KEY (organisation_id) REFERENCES app_organization (id)');
        $this->addSql('ALTER TABLE app_organization_addresse ADD CONSTRAINT FK_822C52DEF5B7AF75 FOREIGN KEY (address_id) REFERENCES app_address (id)');
        $this->addSql('ALTER TABLE app_social_link_organization ADD CONSTRAINT FK_F265EF05A04AF86 FOREIGN KEY (organization_source) REFERENCES app_organization (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_social_link_organization ADD CONSTRAINT FK_F265EF043E1FF09 FOREIGN KEY (organization_target) REFERENCES app_organization (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_property_value ADD CONSTRAINT FK_49E0BC72DB210BC FOREIGN KEY (webPage_id) REFERENCES app_web_page (id)');
        $this->addSql('ALTER TABLE app_property_value ADD CONSTRAINT FK_49E0BC77294869C FOREIGN KEY (article_id) REFERENCES app_article (id)');
        $this->addSql('ALTER TABLE app_property_value ADD CONSTRAINT FK_49E0BC72F6991A8 FOREIGN KEY (localBusiness_id) REFERENCES app_local_business (id)');
        $this->addSql('ALTER TABLE app_category_searchaction ADD CONSTRAINT FK_3B4A978AA3A51EE0 FOREIGN KEY (searchaction_id) REFERENCES app_search_action (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_category_searchaction ADD CONSTRAINT FK_3B4A978A12469DE2 FOREIGN KEY (category_id) REFERENCES app_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_service ADD CONSTRAINT FK_CC01A9F54B9D732 FOREIGN KEY (icon_id) REFERENCES app_media_object (id)');
        $this->addSql('ALTER TABLE app_service_localbusiness ADD CONSTRAINT FK_29AD27B5ED5CA9E6 FOREIGN KEY (service_id) REFERENCES app_service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_service_localbusiness ADD CONSTRAINT FK_29AD27B5A9F51E7F FOREIGN KEY (localbusiness_id) REFERENCES app_local_business (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_special_announcement ADD CONSTRAINT FK_7E2FFFC27B02E272 FOREIGN KEY (primaryImage_id) REFERENCES app_media_object (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE app_special_announcement ADD CONSTRAINT FK_7E2FFFC212469DE2 FOREIGN KEY (category_id) REFERENCES app_category (id)');
        $this->addSql('ALTER TABLE app_special_announcement_category ADD CONSTRAINT FK_902B544DADEF5AF8 FOREIGN KEY (specialannouncement_id) REFERENCES app_special_announcement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_special_announcement_category ADD CONSTRAINT FK_902B544D12469DE2 FOREIGN KEY (category_id) REFERENCES app_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_special_announcement_translation ADD CONSTRAINT FK_CA4762AE2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES app_special_announcement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_web_page ADD CONSTRAINT FK_D888DB787B02E272 FOREIGN KEY (primaryImage_id) REFERENCES app_media_object (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE app_web_page ADD CONSTRAINT FK_D888DB7812469DE2 FOREIGN KEY (category_id) REFERENCES app_category (id)');
        $this->addSql('ALTER TABLE app_web_page_category ADD CONSTRAINT FK_7D146DF1E20F2920 FOREIGN KEY (webpage_id) REFERENCES app_web_page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_web_page_category ADD CONSTRAINT FK_7D146DF112469DE2 FOREIGN KEY (category_id) REFERENCES app_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_web_page_translation ADD CONSTRAINT FK_BEE1C5B02C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES app_web_page (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_organization_addresse DROP FOREIGN KEY FK_822C52DEF5B7AF75');
        $this->addSql('ALTER TABLE app_article_category DROP FOREIGN KEY FK_C79A0C0B7294869C');
        $this->addSql('ALTER TABLE app_article_translation DROP FOREIGN KEY FK_7054C7CD2C2AC5D3');
        $this->addSql('ALTER TABLE app_property_value DROP FOREIGN KEY FK_49E0BC77294869C');
        $this->addSql('ALTER TABLE app_article DROP FOREIGN KEY FK_EF678E2B12469DE2');
        $this->addSql('ALTER TABLE app_article_category DROP FOREIGN KEY FK_C79A0C0B12469DE2');
        $this->addSql('ALTER TABLE app_category_localbusiness DROP FOREIGN KEY FK_D28F1C1912469DE2');
        $this->addSql('ALTER TABLE app_category_translation DROP FOREIGN KEY FK_E8A28FD32C2AC5D3');
        $this->addSql('ALTER TABLE app_landing_page DROP FOREIGN KEY FK_4529D21812469DE2');
        $this->addSql('ALTER TABLE app_organization DROP FOREIGN KEY FK_36079FD12469DE2');
        $this->addSql('ALTER TABLE app_category_searchaction DROP FOREIGN KEY FK_3B4A978A12469DE2');
        $this->addSql('ALTER TABLE app_special_announcement DROP FOREIGN KEY FK_7E2FFFC212469DE2');
        $this->addSql('ALTER TABLE app_special_announcement_category DROP FOREIGN KEY FK_902B544D12469DE2');
        $this->addSql('ALTER TABLE app_web_page DROP FOREIGN KEY FK_D888DB7812469DE2');
        $this->addSql('ALTER TABLE app_web_page_category DROP FOREIGN KEY FK_7D146DF112469DE2');
        $this->addSql('ALTER TABLE app_cms_style DROP FOREIGN KEY FK_B5A17C7363885885');
        $this->addSql('ALTER TABLE app_cms_template DROP FOREIGN KEY FK_3F00C9FF63885885');
        $this->addSql('ALTER TABLE app_landing_page_translation DROP FOREIGN KEY FK_90BE53BB2C2AC5D3');
        $this->addSql('ALTER TABLE app_category_localbusiness DROP FOREIGN KEY FK_D28F1C19A9F51E7F');
        $this->addSql('ALTER TABLE app_message DROP FOREIGN KEY FK_5BE0B0322F6991A8');
        $this->addSql('ALTER TABLE app_opening_hours_specification DROP FOREIGN KEY FK_D5865C882F6991A8');
        $this->addSql('ALTER TABLE app_property_value DROP FOREIGN KEY FK_49E0BC72F6991A8');
        $this->addSql('ALTER TABLE app_service_localbusiness DROP FOREIGN KEY FK_29AD27B5A9F51E7F');
        $this->addSql('ALTER TABLE app_article DROP FOREIGN KEY FK_EF678E2B7B02E272');
        $this->addSql('ALTER TABLE app_article DROP FOREIGN KEY FK_EF678E2B29C1004E');
        $this->addSql('ALTER TABLE app_category DROP FOREIGN KEY FK_ECC796C7B02E272');
        $this->addSql('ALTER TABLE app_category DROP FOREIGN KEY FK_ECC796C54B9D732');
        $this->addSql('ALTER TABLE app_landing_page DROP FOREIGN KEY FK_4529D2187B02E272');
        $this->addSql('ALTER TABLE app_organization DROP FOREIGN KEY FK_36079FD7B02E272');
        $this->addSql('ALTER TABLE app_organization DROP FOREIGN KEY FK_36079FD75855086');
        $this->addSql('ALTER TABLE app_organization DROP FOREIGN KEY FK_36079FDC59E054E');
        $this->addSql('ALTER TABLE app_service DROP FOREIGN KEY FK_CC01A9F54B9D732');
        $this->addSql('ALTER TABLE app_special_announcement DROP FOREIGN KEY FK_7E2FFFC27B02E272');
        $this->addSql('ALTER TABLE app_web_page DROP FOREIGN KEY FK_D888DB787B02E272');
        $this->addSql('ALTER TABLE app_local_business DROP FOREIGN KEY FK_44BDD65E537A1329');
        $this->addSql('ALTER TABLE app_media_object DROP FOREIGN KEY FK_D65A2BB3537A1329');
        $this->addSql('ALTER TABLE app_local_business DROP FOREIGN KEY FK_44BDD65E32C8A3DE');
        $this->addSql('ALTER TABLE app_media_object DROP FOREIGN KEY FK_D65A2BB332C8A3DE');
        $this->addSql('ALTER TABLE app_organization DROP FOREIGN KEY FK_36079FD727ACA70');
        $this->addSql('ALTER TABLE app_organization_addresse DROP FOREIGN KEY FK_822C52DE9E6B1585');
        $this->addSql('ALTER TABLE app_social_link_organization DROP FOREIGN KEY FK_F265EF05A04AF86');
        $this->addSql('ALTER TABLE app_social_link_organization DROP FOREIGN KEY FK_F265EF043E1FF09');
        $this->addSql('ALTER TABLE app_message DROP FOREIGN KEY FK_5BE0B032F624B39D');
        $this->addSql('ALTER TABLE app_category_searchaction DROP FOREIGN KEY FK_3B4A978AA3A51EE0');
        $this->addSql('ALTER TABLE app_service_localbusiness DROP FOREIGN KEY FK_29AD27B5ED5CA9E6');
        $this->addSql('ALTER TABLE app_special_announcement_category DROP FOREIGN KEY FK_902B544DADEF5AF8');
        $this->addSql('ALTER TABLE app_special_announcement_translation DROP FOREIGN KEY FK_CA4762AE2C2AC5D3');
        $this->addSql('ALTER TABLE app_property_value DROP FOREIGN KEY FK_49E0BC72DB210BC');
        $this->addSql('ALTER TABLE app_web_page_category DROP FOREIGN KEY FK_7D146DF1E20F2920');
        $this->addSql('ALTER TABLE app_web_page_translation DROP FOREIGN KEY FK_BEE1C5B02C2AC5D3');
        $this->addSql('DROP TABLE app_address');
        $this->addSql('DROP TABLE app_article');
        $this->addSql('DROP TABLE app_article_category');
        $this->addSql('DROP TABLE app_article_translation');
        $this->addSql('DROP TABLE app_category');
        $this->addSql('DROP TABLE app_category_localbusiness');
        $this->addSql('DROP TABLE app_category_translation');
        $this->addSql('DROP TABLE app_cms_component');
        $this->addSql('DROP TABLE app_cms_style');
        $this->addSql('DROP TABLE app_cms_template');
        $this->addSql('DROP TABLE app_landing_page');
        $this->addSql('DROP TABLE app_landing_page_translation');
        $this->addSql('DROP TABLE app_local_business');
        $this->addSql('DROP TABLE app_media_object');
        $this->addSql('DROP TABLE app_message');
        $this->addSql('DROP TABLE app_opening_hours_specification');
        $this->addSql('DROP TABLE app_organization');
        $this->addSql('DROP TABLE app_organization_addresse');
        $this->addSql('DROP TABLE app_social_link_organization');
        $this->addSql('DROP TABLE app_person');
        $this->addSql('DROP TABLE app_place');
        $this->addSql('DROP TABLE app_property_value');
        $this->addSql('DROP TABLE app_search_action');
        $this->addSql('DROP TABLE app_category_searchaction');
        $this->addSql('DROP TABLE app_service');
        $this->addSql('DROP TABLE app_service_localbusiness');
        $this->addSql('DROP TABLE app_special_announcement');
        $this->addSql('DROP TABLE app_special_announcement_category');
        $this->addSql('DROP TABLE app_special_announcement_translation');
        $this->addSql('DROP TABLE app_user_group');
        $this->addSql('DROP TABLE app_web_page');
        $this->addSql('DROP TABLE app_web_page_category');
        $this->addSql('DROP TABLE app_web_page_translation');
    }
}
