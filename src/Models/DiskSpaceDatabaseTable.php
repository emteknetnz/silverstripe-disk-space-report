<?php

namespace Emteknetnz\DiskSpaceReport\Models;

use SilverStripe\ORM\DataObject;

/**
 * Disk space usage of a single database table
 */
class DiskSpaceDatabaseTable extends DataObject
{
    private static array $db = [
        'Name' => 'Varchar',
        'SizeMB' => 'Float',
    ];

    private static array $summary_fields = [
        'Name' => 'Database table',
        'SizeMB' => 'Size (MB)',
    ];

    private static string $table_name = 'DiskSpaceDatabaseTable';

    private static string $default_sort = 'Size DESC';
}
