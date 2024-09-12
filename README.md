# Silverstripe Disk Space Report

This module provides an admin only report that shows the disk space used by each table in the database.

This module works on Silverstripe 4 and 5.

It has only been tested on MySQL and MariaDB databases.

## Installation

```bash
composer require emteknetnz/silverstripe-disk-space-report
```

## Usage

Once installed, you can view the report by going to the `Reports` section in the CMS. You must be an admin to view this report.

A queued job will be created on the first `dev/build` which will calculate the disk space used by each table in the database.

This job will run immediately at first, and after that will run every 24 hours to keep the data up to date.

## Configuration

You can configure how often the job runs with the following configuration:

```yml
Emteknetnz\DiskSpaceReport\Jobs\DiskSpaceJob:
  # Run every 12 hours instead of the default 24 hours
  run_every_seconds: 43200
```
