<?php

namespace Emteknetnz\DiskSpaceReport\Reports;

use Emteknetnz\DiskSpaceReport\Models\DiskSpaceDatabaseTable;
use Emteknetnz\DiskSpaceReport\Models\DiskSpaceDirectory;
use SilverStripe\Reports\Report;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;

class DiskSpaceDirectoryReport extends Report
{
    protected $title = 'Disk space directory report';

    protected $description = 'Directory sizes on the filesystem';

    public function sourceRecords()
    {
        return DiskSpaceDirectory::get();
    }

    public function canView($member = null)
    {
        if (!$member) {
            $member = Security::getCurrentUser();
        }
        return Permission::checkMember($member, 'ADMIN');
    }
}
