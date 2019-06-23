<?php declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class CreateArticlesTable extends AbstractMigration
{
    public function change()
    {
        $this->table('articles', ['id' => false, 'primary_key' => ['id', 'feed_id']])
            ->addColumn('id', 'uuid')
            ->addColumn('source_id', 'string')
            ->addColumn('feed_id', 'string')
            ->addColumn('url', 'string')
            ->addColumn('source', 'string')
            ->addColumn('title', 'string', ['limit' => 512])
            ->addColumn('excerpt', 'text')
            ->addColumn('created_at', 'datetime')
            ->addForeignKey('feed_id', 'feeds', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
            ->addIndex('id', ['unique' => true])
            ->addIndex('created_at')
            ->create()
        ;
    }
}
