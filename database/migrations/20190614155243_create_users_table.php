<?php declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    public function change()
    {
        $this->table('users', ['id' => false, 'primary_key' => 'id'])
            ->addColumn('id', 'uuid')
            ->addColumn('email_address', 'string', ['limit' => 128])
            ->addColumn('password', 'string')
            ->addIndex('email_address', ['unique' => true])
            ->create()
        ;
    }
}
