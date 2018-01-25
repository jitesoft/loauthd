<?php
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;
use LaravelDoctrine\Migrations\Schema\Builder;
use LaravelDoctrine\Migrations\Schema\Table;

class Version20180117133100 extends AbstractMigration {

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {

        (new Builder($schema))->create('oauth2_clients', function(Table $table) {
            $table->increments('id');
            $table->string('identifier')->setNotnull(true);
            $table->string('name')->setNotnull(true);
            $table->text('redirect_url')->setNotnull(true);
            $table->string('secret')->setNotnull(false);
            $table->smallInteger('grants')->setDefault(0);

            $table->unique('identifier');
        });

        (new Builder($schema))->create('oauth2_access_tokens', function(Table $table) {
            $table->increments('id');
            $table->string('identifier')->setNotnull(true);
            $table->integer('client_id', false, true)->setNotnull(true);
            $table->string('user_identifier')->setNotnull(false);
            $table->timestamp('expiry')->setNotnull(true);

            $table->index('user_identifier');
            $table->unique('identifier');
            $table->foreign('oauth2_clients', 'client_id', 'id', [], 'access_token_client_foreign');
        });


        (new Builder($schema))->create('oauth2_auth_codes', function(Table $table) {
            $table->increments('id');
            $table->string('identifier')->setNotnull(true);
            $table->integer('client_id', false, true)->setNotnull(true);
            $table->string('redirect_uri')->setNotnull(true);

            $table->unique('identifier');
            $table->foreign('oauth2_clients', 'client_id', 'id', [], 'auth_code_client_foreign');
        });

        (new Builder($schema))->create('oauth2_refresh_tokens', function(Table $table) {
            $table->increments('id');
            $table->string('identifier')->setNotnull(true);
            $table->integer('access_token_id', false, true);
            $table->timestamp('expiry')->setNotnull(true);

            $table->unique('identifier');
            $table->foreign('oauth2_access_tokens', 'access_token_id', 'id', [], 'refresh_token_access_token_foreign');
        });


        (new Builder($schema))->create('oauth2_scopes', function(Table $table) {
            $table->increments('id');
            $table->string('identifier')->setNotnull(true);
            $table->string('scope_name')->setNotnull(false);
        });

        (new Builder($schema))->create('oauth2_token_scopes', function(Table $table) {
            $table->increments('id');
            $table->integer('auth_code_id', false, true)->setNotnull(false);
            $table->integer('access_token_id', false, true)->setNotnull(false);
            $table->integer('scope_id', false, true)->setNotnull(true);

            $table->foreign('oauth2_scopes','scope_id', 'id', [], 'token_scope_scope_foreign');
            $table->foreign('oauth2_access_tokens', 'access_token_id', 'id', [], 'token_scope_access_token_foreign');
            $table->foreign('oauth2_auth_codes', 'auth_code_id', 'id', [], 'token_scope_auth_code_foreign');
        });

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {

        $builder = new Builder($schema);

        $table = $builder->table('oauth2_token_scopes', function(Table $table) {});
        $table->removeForeignKey('token_scope_scope_foreign');
        $table->removeForeignKey('token_scope_access_token_foreign');
        $table->removeForeignKey('token_scope_auth_code_foreign');

        $table = $builder->table('oauth2_auth_codes', function(Table $table) {});
        $table->removeForeignKey('auth_code_client_foreign');

        $table = $builder->table('oauth2_access_tokens', function(Table $table) {});
        $table->removeForeignKey('access_token_client_foreign');

        $table = $builder->table('oauth2_refresh_tokens', function(Table $table){});
        $table->removeForeignKey('refresh_token_access_token_foreign');

        $builder->drop('oauth2_token_scopes');
        $builder->drop('oauth2_scopes');
        $builder->drop('oauth2_refresh_tokens');
        $builder->drop('oauth2_auth_codes');
        $builder->drop('oauth2_access_tokens');
        $builder->drop('oauth2_clients');
    }

}
