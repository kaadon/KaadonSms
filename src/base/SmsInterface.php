<?php
/**
 *   +----------------------------------------------------------------------
 *   | PROJECT:   [ KaadonSms ]
 *   +----------------------------------------------------------------------
 *   | 官方网站:   [ https://developer.kaadon.com ]
 *   +----------------------------------------------------------------------
 *   | Author:    [ kaadon.com <kaadon.com@gmail.com>]
 *   +----------------------------------------------------------------------
 *   | Tool:      [ PhpStorm ]
 *   +----------------------------------------------------------------------
 *   | Date:      [ 2024/8/15 ]
 *   +----------------------------------------------------------------------
 *   | 版权所有    [ 2020~2024 kaadon.com ]
 *   +----------------------------------------------------------------------
 **/

namespace Kaadon\KaadonSms\base;

interface SmsInterface
{

    public function sendContent(): array;

    public function verifyCode(?array $params): bool;

    public function sendCode(): array;

    public function batchSendCode():array;

    public function batchSendContent():array;

}