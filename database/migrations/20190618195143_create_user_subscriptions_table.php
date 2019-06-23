<?php declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class CreateUserSubscriptionsTable extends AbstractMigration
{
    public function change()
    {
        $this->table('user_subscriptions', ['id' => false, 'primary_key' => 'id'])
            ->addColumn('id', 'uuid')
            ->addColumn('feed_id', 'string')
            ->addColumn('category_id', 'uuid')
            ->addForeignKey('feed_id', 'feeds', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
            ->addForeignKey('category_id', 'user_categories', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
            ->addIndex(['feed_id', 'category_id'], ['unique' => true])
            ->create()
        ;
    }
}
