<?php
/**
 *   +----------------------------------------------------------------------
 *   | PROJECT:   [ kaadonSms ]
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

namespace kaadon\KaadonSms;

use Kaadon\KaadonSms\base\KaadonSmsException;

/**
 *
 */
class SmsProvider
{
    /**
     * @throws \Kaadon\KaadonSms\base\KaadonSmsException
     */
    public static function send(string $mobile, string $content, string $code, SmsProviderEnum $provider, array $config = []): array
    {
        $config = array_merge($provider->getConfig() ?: [], $config);
        if (empty($config)) throw new KaadonSmsException('短信平台[' . $provider->label() . ']配置不能为空');
        $providerClass = new ($provider->getClass())($config, $mobile, $content, $code);
        return $providerClass->sendContent();
    }

    /**
     * @throws \Kaadon\KaadonSms\base\KaadonSmsException
     */
    public static function verify(array $params, SmsProviderEnum $provider, array $config = []): bool
    {
        $config = array_merge($provider->getConfig() ?: [], $config);
        if (empty($config)) throw new KaadonSmsException('短信平台[' . $provider->label() . ']配置不能为空');
        $mobile        = $params['mobile'] ?? throw new KaadonSmsException('手机号必须存在');
        $code          = $params['code'] ?? throw new KaadonSmsException('短信验证码必须存在');
        $providerClass = new ($provider->getClass())($config, $mobile, null, $code);
        return $providerClass->verifyCode($params);
    }

    /**
     * 获取短信平台列表
     * @return array
     */
    public static function getProviderCases(): array
    {
        return array_map(function ($item) {
            return [
                'value'  => $item->value,
                'label'  => $item->label(),
                'class'  => $item->getClass(),
                'config' => $item->getConfig()
            ];
        }, SmsProviderEnum::cases());
    }
}