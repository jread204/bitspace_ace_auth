<?php

namespace Ace_auth\Exception;

/**
 * Package Exception
 *
 * Exception throws when something goes wrong with adding, editing
 * or removing packages from users.
 *
 * @package       Solution10
 * @category      Auth
 * @author        Alex Gisby <alex@solution10.com>
 * @license       MIT
 */
class Package extends \Exception
{
    const PACKAGE_NOT_FOUND = 1;
    const PACKAGE_BAD_LINEAGE = 2;
    const BAD_PERMISSION_VALUE = 3;
}
