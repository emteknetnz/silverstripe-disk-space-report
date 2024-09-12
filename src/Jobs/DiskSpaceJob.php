<?php

namespace Emteknetnz\DiskSpaceReport\Jobs;

use Emteknetnz\DiskSpaceReport\Models\DiskSpaceDatabaseTable;
use SilverStripe\Core\Config\Configurable;
use Symbiote\QueuedJobs\Services\AbstractQueuedJob;
use Symbiote\QueuedJobs\Services\QueuedJobService;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\ORM\DB;

/**
 * Reads disk space usage of the website
 */
class DiskSpaceJob extends AbstractQueuedJob
{
    use Configurable;

    /**
     * How often the job should run in seconds - default is 24 hours
     */
    private static int $run_every_seconds = 86400;

    public function getTitle(): string
    {
        return 'Disk space job';
    }

    /**
     * Read disk space usage of the website
     */
    public function process(): void
    {
        $this->createNextJob();
        $this->readDatabaseTableSizes();
        $this->isComplete = true;
    }

    private function readDatabaseTableSizes(): void
    {
        DiskSpaceDatabaseTable::get()->removeAll();
        $tables = DB::query('SHOW TABLE STATUS');
        foreach ($tables as $table) {
            $name = $table['Name'];
            $size = $table['Data_length'] + $table['Index_length'];
            DiskSpaceDatabaseTable::create([
                'Name' => $name,
                'SizeMB' => round($size / 1024 / 1024, 2),
            ])->write();
        }
    }

    private function createNextJob(): void
    {
        $this->addMessage('Queueing the next ' . strtolower($this->getTitle()));
        $job = Injector::inst()->create(static::class);
        $runEverySeconds = static::config()->get('run_every_seconds');
        $timestamp = DBDatetime::now()->getTimestamp() + $runEverySeconds;
        $startAfter = DBDatetime::create()->setValue($timestamp)->Rfc2822();
        QueuedJobService::singleton()->queueJob($job, $startAfter);
    }
}
