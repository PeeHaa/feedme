<?php declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class CreateReadStatusTable extends AbstractMigration
{
    public function change()
    {
        $this->table('read_status', ['id' => false, 'primary_key' => 'id'])
            ->addColumn('id', 'uuid')
            ->addColumn('user_id', 'uuid')
            ->addColumn('article_id', 'uuid')
            ->addColumn('created_at', 'datetime')
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
            ->addForeignKey('article_id', 'articles', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
            ->addIndex(['user_id', 'article_id'], ['unique' => true])
            ->create()
        ;
    }
}
