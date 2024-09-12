<?php

namespace Emteknetnz\DiskSpaceReport\Extensions;

use SilverStripe\Core\Extension;
use Symbiote\QueuedJobs\DataObjects\QueuedJobDescriptor;
use Emteknetnz\DiskSpaceReport\Jobs\DiskSpaceJob;

/**
 * @extends Extension<QueuedJobDescriptor>
 */
class QueuedJobDescriptorExtension extends Extension
{
    /**
     * Called on dev/build by DatabaseAdmin
     */
    public function onAfterBuild(): void
    {
        DiskSpaceJob::singleton()->requireDefaultJob();
    }
}
