<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210521135831 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_cliente_group DROP FOREIGN KEY FK_50C3B4A6791488BD');
        $this->addSql('DROP TABLE user_cliente');
        $this->addSql('DROP TABLE user_cliente_group');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_cliente (id INT AUTO_INCREMENT NOT NULL, sucursal_id INT DEFAULT NULL, cliente_id INT DEFAULT NULL, username VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, username_canonical VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email_canonical VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', deleted_at DATETIME DEFAULT NULL, facility VARCHAR(60) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, street VARCHAR(150) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_AC11852F92FC23A8 (username_canonical), UNIQUE INDEX UNIQ_AC11852FC05FB297 (confirmation_token), INDEX IDX_AC11852FDE734E51 (cliente_id), UNIQUE INDEX UNIQ_AC11852FA0D96FBF (email_canonical), INDEX IDX_AC11852F279A5D5E (sucursal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_cliente_group (user_cliente_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_50C3B4A6FE54D947 (group_id), INDEX IDX_50C3B4A6791488BD (user_cliente_id), PRIMARY KEY(user_cliente_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_cliente ADD CONSTRAINT FK_AC11852F279A5D5E FOREIGN KEY (sucursal_id) REFERENCES sucursal (id)');
        $this->addSql('ALTER TABLE user_cliente ADD CONSTRAINT FK_AC11852FDE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('ALTER TABLE user_cliente_group ADD CONSTRAINT FK_50C3B4A6791488BD FOREIGN KEY (user_cliente_id) REFERENCES user_cliente (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_cliente_group ADD CONSTRAINT FK_50C3B4A6FE54D947 FOREIGN KEY (group_id) REFERENCES groups (id) ON DELETE CASCADE');
    }
}
