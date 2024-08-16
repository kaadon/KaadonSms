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
 *   | Date:      [ 2024/7/29 ]
 *   +----------------------------------------------------------------------
 *   | 版权所有    [ 2020~2024 kaadon.com ]
 *   +----------------------------------------------------------------------
 **/

namespace Kaadon\KaadonSms\base;

/**
 *
 */
abstract class SmsBase implements SmsInterface
{

    /**
     * @var array
     */
    protected array $config = [];

    /**
     * @var string
     */
    protected string $mobile;

    /**
     * @var string
     */
    protected string $content;


    /**
     * @var bool
     */
    protected bool   $is_verify_remote = false; // 是否本地验证
    /**
     * @var string
     */
    protected string $code;

    /**
     * @param array $config
     * @param string $mobile
     * @param string|null $content
     */
    public function __construct(array $config, string $mobile, ?string $content = null)
    {
        $this->config = $config;
        $this->mobile = $mobile;
        if ($content) $this->content = $content;
    }


    /**
     * @param string $content
     * @return void
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @throws \Exception
     */
    public function verifyCode(?array $params): bool
    {
        $code = Cache('sms_code_' . md5($this->mobile));
        if (empty($code)) return false;
        if ($this->is_verify_remote) {
            if (!method_exists($this, '__verifyCode')) throw new \Exception('请实现__verifyCode方法');
            return $this->__verifyCode([
                'params' => $params,
                'result' => $code
            ]);
        } else {
            if (!isset($params['code'])) throw new KaadonSmsException('短信验证码必须存在');
            return (string)$code === (string)$params['code'];
        }
    }

    /**
     * @throws \Kaadon\KaadonSms\base\KaadonSmsException
     */
    public function sendContent(): array
    {
        if (empty($this->content)) throw new KaadonSmsException('短信内容不能为空');
        if (!method_exists(self::class, '__sendContent')) throw new KaadonSmsException('请实现__sendContent方法');
        return $this->__sendContent();
    }

    /**
     * @param string|null $code
     * @param int $length
     * @return array
     * @throws KaadonSmsException
     */
    public function sendCode(?string $code = null, int $length = 4): array
    {
        if (!empty($code)) $this->code = $code;
        if (empty($this->code)) $this->code = (string)($length === 4 ? rand(1111, 9999) : rand(111111, 999999));
        if (empty($this->content)) $this->content = '您的验证码是：{code}';
        if (!str_contains($this->content, '{code}')) throw new KaadonSmsException('短信内容必须包含{code}');
        $this->content = str_replace('{code}', $this->code, $this->content);
        if (!method_exists(self::class, '__sendContent')) throw new KaadonSmsException('请实现__sendContent方法');
        $result = $this->__sendContent();
        if ($this->is_verify_remote) Cache('sms_code_' . md5($this->mobile), $result, 300); else Cache('sms_code_' . md5($this->mobile), $this->code, 300);
        return $result;
    }

    /**
     * @throws KaadonSmsException
     */
    public function batchSendCode(): array
    {
        throw new KaadonSmsException('暂不支持批量发送验证码');
    }

    /**
     * @return array
     * @throws KaadonSmsException
     */
    public function batchSendContent(): array
    {
        throw new KaadonSmsException('暂不支持批量发送内容');;
    }

    /**
     * @param array|null $params
     * @return bool
     */
    private function __verifyCode(?array $params = null): bool
    {
        return true;
    }

    /**
     * @return array
     */
    private function __sendContent(): array
    {
        return [];
    }



}