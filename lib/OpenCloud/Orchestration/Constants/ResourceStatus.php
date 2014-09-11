<?php
namespace OpenCloud\Orchestration\Constants;

class ResourceStatus extends StackStatus
{
    const INIT_IN_PROGRESS = 'INIT_IN_PROGRESS';
    const INIT_COMPLETE = 'INIT_COMPLETE';
    const INIT_FAILED = 'INIT_FAILED';
}