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
 *   | Date:      [ 2024/7/29 ]
 *   +----------------------------------------------------------------------
 *   | 版权所有    [ 2020~2024 kaadon.com ]
 *   +----------------------------------------------------------------------
 **/

namespace kaadon\Sms\base;

abstract class SmsBase
{

    protected array $config = [];

    protected string $mobile;

    protected string $content;

    protected string $code;

    protected bool $is_verify_remote = false; // 是否本地验证

    public function __construct(array $config, string $mobile, ?string $content = null, ?string $code = null)
    {
        $this->config = $config;
        $this->mobile = $mobile;
        if ($content) $this->content = $content;
        if ($code) $this->code = $code;
    }

    /**
     * @throws \Exception
     */
    public function verifyCode(?array $params): bool
    {
        $code = redisCacheGet('sms_code_' . md5($this->mobile));
        if (empty($code)) return false;
        if ($this->is_verify_remote) return $this->__verifyCode([
            'params' => $params,
            'code' => $code
        ]);else throw new \Exception('无需远程验证请使用本地验证') ;
    }

    /**
     * @throws \kaadon\Sms\base\KaadonSmsException
     */
    public function sendContent(): array
    {
        if (empty($this->content)) throw new KaadonSmsException('短信内容不能为空');
        return $this->__sendContent();
    }

    /**
     * @throws \Exception
     */
    public function sendCode(?string $code = null, $length = 4): array
    {
        if (!empty($code)) $this->code = $code;
        if (empty($this->code)) $this->code = (string)($length === 4 ? rand(1111, 9999) : rand(111111, 999999));
        if (empty($this->content)) $this->content = '您的验证码是：{code}';
        if (!str_contains($this->content, '{code}')) throw new KaadonSmsException('短信内容必须包含{code}');
        $this->content = str_replace('{code}', $this->code, $this->content);
        return $this->__sendContent();
    }

    protected function __verifyCode(?array $params = null): bool
    {
        return true;
    }

    protected function __sendContent(): array
    {
        return [];
    }

}