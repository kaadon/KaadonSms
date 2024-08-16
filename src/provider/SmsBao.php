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

namespace Kaadon\KaadonSms\provider;

use Kaadon\KaadonSms\base\KaadonSmsException;
use Kaadon\KaadonSms\base\SmsBase;

/**
 * 短信宝
 */
class SmsBao extends SmsBase
{
    /**
     * @var bool
     */
    protected bool $is_verify_remote = false;
    /**
     * @var array|string[]
     */
    public array $statusStr = array(
        "0" => "短信发送成功",
        "-1" => "参数不全",
        "-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
        "30" => "参数:密码错误",
        "40" => "参数:账号不存在",
        "41" => "余额不足",
        "42" => "帐户已过期",
        "43" => "IP地址限制",
        "50" => "内容含有敏感词"
    );

    /**
     * @param string $url
     * @return void
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }
    /**
     * @var string
     */
    public string $url = "https://api.smsbao.com/";

    /**
     * @throws KaadonSmsException
     */
    public function __construct(array $config, string $mobile, ?string $content)
    {
        if (!isset($config['user'])) throw new KaadonSmsException('短信平台帐号不能为空');
        if (!isset($config['pass'])) throw new KaadonSmsException('短信平台帐号不能为空');
        parent::__construct($config, $mobile, $content);
    }


    /**
     * @throws \Kaadon\KaadonSms\base\KaadonSmsException
     */
    private function __sendContent(): array
    {
        $sendurl = $this->url . "sms?u=" . $this->config['user'] . "&p=" . md5($this->config['pass']) . "&m=" . $this->mobile . "&c=" . urlencode($this->content);
        $result = file_get_contents($sendurl);
        if((string)$result === "0"){
            return [
                'code' => $this->code,
                'msg' => $this->statusStr[$result]
            ];
        }else{
            throw new KaadonSmsException($this->statusStr[$result]);
        }
    }
}