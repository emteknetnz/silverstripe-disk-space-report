<?php

namespace Emteknetnz\DiskSpaceReport\Models;

use SilverStripe\ORM\DataObject;

/**
 * Disk space usage of a path on the filesystem
 * Shows total size of all files and directories included within path i.e. also subdirs
 */
class DiskSpaceDirectory extends DataObject
{
    private static array $db = [
        'Path' => 'Varchar',
        'SizeMB' => 'Float',
    ];

    private static array $summary_fields = [
        'Path' => 'Directory',
        'SizeMB' => 'Size (MB)',
    ];

    private static string $table_name = 'DiskSpaceDirectory';

    private static string $default_sort = 'SizeMB DESC';
}
