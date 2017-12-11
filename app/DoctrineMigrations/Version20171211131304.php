<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\AbortMigrationException;

/**
 * Init todo list
 */
class Version20171211131304 extends AbstractMigration
{
    /**
     * Schema up
     *
     * @param Schema $schema
     *
     * @throws AbortMigrationException
     */
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'sqlite',
            'Migration can only be executed safely on \'sqlite\'.'
        );

        $this->addSql('
                        CREATE TABLE user (
                            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                            username VARCHAR(255) NOT NULL, 
                            password VARCHAR(255) NOT NULL, 
                            created_at DATETIME NOT NULL
                            )
                    ');

        $this->addSql('
                        CREATE TABLE task (
                            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
                            user_id INT NOT NULL, 
                            content VARCHAR(255) NOT NULL, 
                            is_completed BOOLEAN DEFAULT \'0\' NOT NULL, 
                            created_at DATETIME NOT NULL
                            )
                    ');

        $this->addSql('CREATE INDEX IDX_527EDB25A76ED395 ON task (user_id)');
    }

    /**
     * Schema down
     *
     * @param Schema $schema
     *
     * @throws AbortMigrationException
     */
    public function down(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'sqlite',
            'Migration can only be executed safely on \'sqlite\'.'
        );

        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE task');
    }
}
