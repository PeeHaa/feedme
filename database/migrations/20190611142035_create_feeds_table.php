<?php declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class CreateFeedsTable extends AbstractMigration
{
    public function change()
    {
        $this->table('feeds', ['id' => false, 'primary_key' => 'id'])
            ->addColumn('id', 'string')
            ->addColumn('crawler', 'string')
            ->addColumn('interval', 'string')
            ->create()
        ;
    }
}
