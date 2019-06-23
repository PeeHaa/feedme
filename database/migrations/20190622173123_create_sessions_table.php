<?php declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class CreateSessionsTable extends AbstractMigration
{
    public function change()
    {
        $this->table('sessions', ['id' => false, 'primary_key' => 'id'])
            ->addColumn('id', 'uuid')
            ->addColumn('client_id', 'integer', ['signed' => false])
            ->addColumn('user_id', 'uuid')
            ->addColumn('token', 'uuid')
            ->addColumn('expiration', 'datetime')
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
            ->addIndex(['id', 'user_id'])
            ->create()
        ;
    }
}
