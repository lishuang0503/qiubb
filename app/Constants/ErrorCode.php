<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("Server Error！")
     */
    public const SERVER_ERROR = 500;
    /**
     * @Message("missing token！")
     */
    public const MISSING_TOKEN = -40001;


    /**
     * @Message("SUCCESS")
     */
    public const SUCCESS = 0;


    /**
     * @Message("BAD_REQUEST")
     */
    public const BAD_REQUEST = -1;



    /**
     * @Message("login failed")
     */
    public const LOGIN_FAILED = -50001;

}
