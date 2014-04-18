<?php
/**
 * Copyright 2012-2014 Rackspace US, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace OpenCloud\Compute\Constants;

class ServerState
{
    /**
     * The server is active and ready to use.
     */
    const ACTIVE = 'ACTIVE';

    /**
     * The server is being built.
     */
    const BUILD = 'BUILD';

    /**
     * The server was deleted. The list servers API operation does not show servers with a status of DELETED. To list
     * deleted servers, use the changes-since parameter.
     */
    const DELETED = 'DELETED';

    /**
     * The requested operation failed and the server is in an error state.
     */
    const ERROR = 'ERROR';

    /**
     * The server is going through a hard reboot. This power cycles your server, which performs an immediate shutdown
     * and restart.
     */
    const HARD_REBOOT = 'HARD_REBOOT';

    /**
     * The server is being moved from one physical node to another physical node. Server migration is a Rackspace extension.
     */
    const MIGRATING = 'MIGRATING';

    /**
     * The password for the server is being changed.
     */
    const PASSWORD = 'PASSWORD';

    /**
     * The server is going through a soft reboot. During a soft reboot, the operating system is signaled to restart,
     * which allows for a graceful shutdown and restart of all processes.
     */
    const REBOOT = 'REBOOT';

    /**
     * The server is being rebuilt from an image.
     */
    const REBUILD = 'REBUILD';

    /**
     * The server is in rescue mode. Rescue mode is a Rackspace extension.
     */
    const RESCUE = 'RESCUE';

    /**
     * The server is being resized and is inactive until it completes.
     */
    const RESIZE = 'RESIZE';

    /**
     * A resized or migrated server is being reverted to its previous size. The destination server is being cleaned up
     * and the original source server is restarting. Server migration is a Rackspace extension.
     */
    const REVERT_RESIZE = 'REVERT_RESIZE';

    /**
     * The server is inactive, either by request or necessity.
     */
    const SUSPENDED = 'SUSPENDED';

    /**
     * The server is in an unknown state.
     */
    const UNKNOWN = 'UNKNOWN';

    /**
     * The server is waiting for the resize operation to be confirmed so that the original server can be removed.
     */
    const VERIFY_RESIZE = 'VERIFY_RESIZE';

    const REBOOT_STATE_HARD = 'HARD';
    const REBOOT_STATE_SOFT = 'SOFT';
}
