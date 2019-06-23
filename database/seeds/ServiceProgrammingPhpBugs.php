<?php declare(strict_types=1);

use PeeHaa\FeedMe\Service\Crawler\Programming\Php\Bugs;
use PeeHaa\FeedMe\Service\Crawler\Programming\Php\DocumentationBugs;
use PeeHaa\FeedMe\Service\Crawler\Programming\Php\Reddit;
use PeeHaa\FeedMe\Service\Crawler\Programming\Php\Releases;
use Phinx\Seed\AbstractSeed;

class ServiceProgrammingPhpBugs extends AbstractSeed
{
    public function getDependencies()
    {
        return [
            'TruncateDatabase',
        ];
    }

    public function run()
    {
        $this->table('feeds')
            ->insert([
                [
                    'id'       => 'Programming.Php.DocumentationBugs',
                    'crawler'  => DocumentationBugs::class,
                    'interval' => 'PT5M',
                ],
                [
                    'id'       => 'Programming.Php.Bugs',
                    'crawler'  => Bugs::class,
                    'interval' => 'PT5M',
                ],
                [
                    'id'       => 'Programming.Php.Releases',
                    'crawler'  => Releases::class,
                    'interval' => 'PT5M',
                ],
                [
                    'id'       => 'Programming.Php.Reddit',
                    'crawler'  => Reddit::class,
                    'interval' => 'PT5M',
                ],
            ])
            ->save()
        ;
    }
}
