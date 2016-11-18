<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160628102102 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE CategoryActivations (id INT AUTO_INCREMENT NOT NULL, Category INT DEFAULT NULL, Vendor INT DEFAULT NULL, INDEX IDX_303E1A31FF3A7B97 (Category), INDEX IDX_303E1A31F28E36C0 (Vendor), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Vendors (id INT AUTO_INCREMENT NOT NULL, Name VARCHAR(255) NOT NULL, ImagePath VARCHAR(255) DEFAULT NULL, Street1 VARCHAR(255) NOT NULL, Street2 VARCHAR(255) DEFAULT NULL, City VARCHAR(75) NOT NULL, State VARCHAR(2) NOT NULL, Zip VARCHAR(5) NOT NULL, Latitude DOUBLE PRECISION DEFAULT NULL, Longitude DOUBLE PRECISION DEFAULT NULL, Owner INT NOT NULL, INDEX IDX_8098838DEA1C978 (Owner), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE AccessTokens (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D66A5F8F5F37A13B (token), INDEX IDX_D66A5F8F19EB6921 (client_id), INDEX IDX_D66A5F8FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Categories (id INT AUTO_INCREMENT NOT NULL, Name VARCHAR(255) NOT NULL, Slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Wishes (id INT AUTO_INCREMENT NOT NULL, Query VARCHAR(255) NOT NULL, Latitude DOUBLE PRECISION NOT NULL, Longitude DOUBLE PRECISION NOT NULL, Created DATETIME NOT NULL, Updated DATETIME DEFAULT NULL, User INT NOT NULL, Vendor INT DEFAULT NULL, Offer INT DEFAULT NULL, INDEX IDX_DAA3A65E2DA17977 (User), INDEX IDX_DAA3A65EF28E36C0 (Vendor), INDEX IDX_DAA3A65EE817A83A (Offer), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Offers (id INT AUTO_INCREMENT NOT NULL, ExpiresAt DATETIME DEFAULT NULL, ExtendedAt DATETIME DEFAULT NULL, RedeemedAt DATETIME DEFAULT NULL, DeletedAt DATETIME DEFAULT NULL, Created DATETIME NOT NULL, Updated DATETIME DEFAULT NULL, Wish INT NOT NULL, Deal INT NOT NULL, User INT NOT NULL, Vendor INT NOT NULL, INDEX IDX_DDEA011177E3DBF7 (Wish), INDEX IDX_DDEA011143CC6E28 (Deal), INDEX IDX_DDEA01112DA17977 (User), INDEX IDX_DDEA0111F28E36C0 (Vendor), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Clients (id INT AUTO_INCREMENT NOT NULL, random_id VARCHAR(255) NOT NULL, redirect_uris LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', secret VARCHAR(255) NOT NULL, allowed_grant_types LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Users (id INT AUTO_INCREMENT NOT NULL, FacebookId VARCHAR(255) NOT NULL, Username VARCHAR(255) NOT NULL, Password VARCHAR(255) DEFAULT NULL, Name VARCHAR(255) NOT NULL, ProfilePhotoUrl VARCHAR(255) NOT NULL, Email VARCHAR(255) NOT NULL, ApiToken VARCHAR(255) NOT NULL, Created DATETIME NOT NULL, Updated DATETIME DEFAULT NULL, Role VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE RefreshTokens (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_CC8337F5F37A13B (token), INDEX IDX_CC8337F19EB6921 (client_id), INDEX IDX_CC8337FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Deals (id INT AUTO_INCREMENT NOT NULL, Title VARCHAR(255) NOT NULL, Caption VARCHAR(255) NOT NULL, Description LONGTEXT NOT NULL, HoursAvailable INT DEFAULT NULL, ExpiresAt DATETIME DEFAULT NULL, DailyDeal TINYINT(1) NOT NULL, Created DATETIME NOT NULL, Updated DATETIME DEFAULT NULL, Vendor INT NOT NULL, INDEX IDX_2EF8AB9FF28E36C0 (Vendor), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE CategoryActivations ADD CONSTRAINT FK_303E1A31FF3A7B97 FOREIGN KEY (Category) REFERENCES Categories (id)');
        $this->addSql('ALTER TABLE CategoryActivations ADD CONSTRAINT FK_303E1A31F28E36C0 FOREIGN KEY (Vendor) REFERENCES Vendors (id)');
        $this->addSql('ALTER TABLE Vendors ADD CONSTRAINT FK_8098838DEA1C978 FOREIGN KEY (Owner) REFERENCES Users (id)');
        $this->addSql('ALTER TABLE AccessTokens ADD CONSTRAINT FK_D66A5F8F19EB6921 FOREIGN KEY (client_id) REFERENCES Clients (id)');
        $this->addSql('ALTER TABLE AccessTokens ADD CONSTRAINT FK_D66A5F8FA76ED395 FOREIGN KEY (user_id) REFERENCES Users (id)');
        $this->addSql('ALTER TABLE Wishes ADD CONSTRAINT FK_DAA3A65E2DA17977 FOREIGN KEY (User) REFERENCES Users (id)');
        $this->addSql('ALTER TABLE Wishes ADD CONSTRAINT FK_DAA3A65EF28E36C0 FOREIGN KEY (Vendor) REFERENCES Vendors (id)');
        $this->addSql('ALTER TABLE Wishes ADD CONSTRAINT FK_DAA3A65EE817A83A FOREIGN KEY (Offer) REFERENCES Offers (id)');
        $this->addSql('ALTER TABLE Offers ADD CONSTRAINT FK_DDEA011177E3DBF7 FOREIGN KEY (Wish) REFERENCES Wishes (id)');
        $this->addSql('ALTER TABLE Offers ADD CONSTRAINT FK_DDEA011143CC6E28 FOREIGN KEY (Deal) REFERENCES Deals (id)');
        $this->addSql('ALTER TABLE Offers ADD CONSTRAINT FK_DDEA01112DA17977 FOREIGN KEY (User) REFERENCES Users (id)');
        $this->addSql('ALTER TABLE Offers ADD CONSTRAINT FK_DDEA0111F28E36C0 FOREIGN KEY (Vendor) REFERENCES Vendors (id)');
        $this->addSql('ALTER TABLE RefreshTokens ADD CONSTRAINT FK_CC8337F19EB6921 FOREIGN KEY (client_id) REFERENCES Clients (id)');
        $this->addSql('ALTER TABLE RefreshTokens ADD CONSTRAINT FK_CC8337FA76ED395 FOREIGN KEY (user_id) REFERENCES Users (id)');
        $this->addSql('ALTER TABLE Deals ADD CONSTRAINT FK_2EF8AB9FF28E36C0 FOREIGN KEY (Vendor) REFERENCES Vendors (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE CategoryActivations DROP FOREIGN KEY FK_303E1A31F28E36C0');
        $this->addSql('ALTER TABLE Wishes DROP FOREIGN KEY FK_DAA3A65EF28E36C0');
        $this->addSql('ALTER TABLE Offers DROP FOREIGN KEY FK_DDEA0111F28E36C0');
        $this->addSql('ALTER TABLE Deals DROP FOREIGN KEY FK_2EF8AB9FF28E36C0');
        $this->addSql('ALTER TABLE CategoryActivations DROP FOREIGN KEY FK_303E1A31FF3A7B97');
        $this->addSql('ALTER TABLE Offers DROP FOREIGN KEY FK_DDEA011177E3DBF7');
        $this->addSql('ALTER TABLE Wishes DROP FOREIGN KEY FK_DAA3A65EE817A83A');
        $this->addSql('ALTER TABLE AccessTokens DROP FOREIGN KEY FK_D66A5F8F19EB6921');
        $this->addSql('ALTER TABLE RefreshTokens DROP FOREIGN KEY FK_CC8337F19EB6921');
        $this->addSql('ALTER TABLE Vendors DROP FOREIGN KEY FK_8098838DEA1C978');
        $this->addSql('ALTER TABLE AccessTokens DROP FOREIGN KEY FK_D66A5F8FA76ED395');
        $this->addSql('ALTER TABLE Wishes DROP FOREIGN KEY FK_DAA3A65E2DA17977');
        $this->addSql('ALTER TABLE Offers DROP FOREIGN KEY FK_DDEA01112DA17977');
        $this->addSql('ALTER TABLE RefreshTokens DROP FOREIGN KEY FK_CC8337FA76ED395');
        $this->addSql('ALTER TABLE Offers DROP FOREIGN KEY FK_DDEA011143CC6E28');
        $this->addSql('DROP TABLE CategoryActivations');
        $this->addSql('DROP TABLE Vendors');
        $this->addSql('DROP TABLE AccessTokens');
        $this->addSql('DROP TABLE Categories');
        $this->addSql('DROP TABLE Wishes');
        $this->addSql('DROP TABLE Offers');
        $this->addSql('DROP TABLE Clients');
        $this->addSql('DROP TABLE Users');
        $this->addSql('DROP TABLE RefreshTokens');
        $this->addSql('DROP TABLE Deals');
    }
}
