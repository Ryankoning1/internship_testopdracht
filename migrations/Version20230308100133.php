<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308100133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            INSERT INTO account
            (
                uuid, 
                email, 
                roles, 
                password, 
                first_name, 
                insertion, 
                last_name, 
                created_on, 
                updated_on
            )
            VALUES
            (
                "a30ebc71-756b-46e4-a10a-daf76b25e93c",
                "admin@admin.nl",
                \'["ROLE_ADMIN"]\',
                "$2y$13$HG3ixBzj6wffiUHdUK3DzuALW1Cjdxy4/YUDNPsEaFN0uDk.yOL3q",
                "Admin",
                "de",
                "User",
                "2023-05-08 12:43:27",
                "2023-05-08 12:43:27"
            )
        ');
        $this->addSql('
            INSERT INTO account
            (
                uuid, 
                email, 
                roles, 
                password, 
                first_name, 
                insertion, 
                last_name, 
                created_on, 
                updated_on
            )
            VALUES
            (
                "15b058d1-b758-46a1-a9b6-f96d74d3c756",
                "user@user.nl",
                \'["ROLE_USER"]\',
                "$2y$13$QWqgRfZTQZlHFufx0iIxT.PyDfEALXIahd0n05aAfmi7ES80SY6te",
                "Test",
                "de",
                "User",
                "2023-05-08 12:43:27",
                "2023-05-08 12:43:27"
            )
        ');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE account');
    }
}
;
