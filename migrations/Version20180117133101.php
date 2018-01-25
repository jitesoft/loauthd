<?php

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use LaravelDoctrine\Migrations\Schema\Builder;
use LaravelDoctrine\Migrations\Schema\Table;

class Version20180117133101 extends AbstractMigration {

    public function up(Schema $schema) {
        (new Builder($schema))->create('oauth2_users', function(Table $table) {
            $table->increments('id');
            $table->string('identifier')->setNotnull(true);
            $table->string('auth_key')->setNotnull(true);
            $table->string('password')->setNotnull(true);

            $table->unique('auth_key');
            $table->unique('identifier');
        });

    }

    public function down(Schema $schema) {
        $schema->dropTable('oauth2_users');
    }
}
