<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220607174901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add updatedAt Datetime column to the product table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product ADD updated_at DATETIME COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product DROP updated_at');
    }
}
