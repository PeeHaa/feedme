<?php declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class CreateUserCategoriesTable extends AbstractMigration
{
    public function change()
    {
        $this->table('user_categories', ['id' => false, 'primary_key' => 'id'])
            ->addColumn('id', 'uuid')
            ->addColumn('user_id', 'uuid')
            ->addColumn('name', 'string')
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
            ->addIndex('name')
            ->create()
        ;
    }
}
