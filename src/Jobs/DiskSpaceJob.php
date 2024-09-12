<?php

namespace Emteknetnz\DiskSpaceReport\Jobs;

use Emteknetnz\DiskSpaceReport\Models\DiskSpaceDatabaseTable;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use Symbiote\QueuedJobs\Services\AbstractQueuedJob;
use Symbiote\QueuedJobs\Services\QueuedJobService;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\ORM\DB;
use Symbiote\QueuedJobs\DataObjects\QueuedJobDescriptor;

/**
 * Job to read disk space usage of the application
 */
class DiskSpaceJob extends AbstractQueuedJob
{
    use Configurable;
    use Injectable;

    /**
     * How often the job should run in seconds - default is 24 hours
     */
    private static int $run_every_seconds = 86400;

    public function getTitle(): string
    {
        return 'Disk space job';
    }

    public function process(): void
    {
        $this->queueNextJob(false);
        $this->readDatabaseTableSizes();
        $this->isComplete = true;
    }

    public function queueNextJob(bool $startImmediately): void
    {
        $this->addMessage('Queueing the next ' . strtolower($this->getTitle()));
        $job = Injector::inst()->create(static::class);
        $runEverySeconds = static::config()->get('run_every_seconds');
        $timestamp = DBDatetime::now()->getTimestamp();
        if (!$startImmediately) {
            $timestamp += $runEverySeconds;
        }
        $startAfter = DBDatetime::create()->setValue($timestamp)->Rfc2822();
        QueuedJobService::singleton()->queueJob($job, $startAfter);
    }

    public function requireDefaultJob(): void
    {
        $count = QueuedJobDescriptor::get()->filter('Implementation', DiskSpaceJob::class)->count();
        if ($count === 0) {
            $this->queueNextJob(true);
        }
    }

    private function readDatabaseTableSizes(): void
    {
        $this->addMessage('Reading database table sizes');
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
}
