<?php declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class TruncateDatabase extends AbstractSeed
{
    public function run()
    {
        $this->execute('TRUNCATE TABLE "feeds" CASCADE');
    }
}
