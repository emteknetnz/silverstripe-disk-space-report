<?php

namespace Emteknetnz\DiskSpaceReport\Reports;

use Emteknetnz\DiskSpaceReport\Models\DiskSpaceDatabaseTable;
use SilverStripe\Reports\Report;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;

class DiskSpaceDatabaseReport extends Report
{
    protected $title = 'Disk space database report';

    protected $description = 'Disk space usage of every database table';

    public function sourceRecords()
    {
        return DiskSpaceDatabaseTable::get();
    }

    public function canView($member = null)
    {
        if (!$member) {
            $member = Security::getCurrentUser();
        }
        return Permission::checkMember($member, 'ADMIN');
    }
}
