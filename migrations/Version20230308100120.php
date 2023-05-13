<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308100120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
        CREATE TABLE account (
            uuid CHAR(36) NOT NULL,
            email VARCHAR(180) NOT NULL,
            roles VARCHAR(255) NOT NULL, 
            password VARCHAR(255) NOT NULL, 
            first_name VARCHAR(50) DEFAULT NULL, 
            insertion VARCHAR(18) DEFAULT NULL, 
            last_name VARCHAR(18) DEFAULT NULL, 
            created_on DATETIME DEFAULT NULL, 
            updated_on DATETIME DEFAULT NULL, 
            PRIMARY KEY(uuid)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D3656A4E7927C74 ON account (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE account');
    }
}
