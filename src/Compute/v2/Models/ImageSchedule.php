<?php

namespace Rackspace\Compute\v2\Models;

use OpenStack\Common\Resource\AbstractResource;

/**
 * Represents an backup schedule for a parent server.
 */
class ImageSchedule extends AbstractResource
{
    const MONDAY = 'MONDAY';
    const TUESDAY = 'TUESDAY';
    const WEDNESDAY = 'WEDNESDAY';
    const THURSDAY = 'THURSDAY';
    const FRIDAY = 'FRIDAY';
    const SATURDAY = 'SATURDAY';
    const SUNDAY = 'SUNDAY';

    /** @var int */
    public $retention;

    /** @var string */
    public $dayOfWeek;

    protected $resourceKey = 'image_schedule';
    protected $aliases = ['day_of_week' => 'dayOfWeek'];
}