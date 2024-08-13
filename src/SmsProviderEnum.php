<?php
/**
 *   +----------------------------------------------------------------------
 *   | PROJECT:   [ machine01_server ]
 *   +----------------------------------------------------------------------
 *   | 官方网站:   [ https://developer.kaadon.com ]
 *   +----------------------------------------------------------------------
 *   | Author:    [ kaadon.com <kaadon.com@gmail.com>]
 *   +----------------------------------------------------------------------
 *   | Tool:      [ PhpStorm ]
 *   +----------------------------------------------------------------------
 *   | Date:      [ 2024/8/7 ]
 *   +----------------------------------------------------------------------
 *   | 版权所有    [ 2020~2024 kaadon.com ]
 *   +----------------------------------------------------------------------
 **/

namespace kaadon\Sms;

use kaadon\Sms\provider\SmsBao;

enum SmsProviderEnum: int
{

    case SMSBAO = 1;

    function label(): string
    {
        return match ($this) {
            self::SMSBAO => '短信宝',
        };
    }

    function getClass(): string
    {
        return match ($this) {
            self::SMSBAO => SmsBao::class,
        };
    }

    function getConfig()
    {
        return match ($this) {
            self::SMSBAO => function_exists("config") ? config('kaadon_sms.' . $this->name) : [],
        };
    }


}
