<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\Site\Tool;

use Larva\Supports\HttpProClient;
use Larva\Supports\HttpResponse;

/**
 * 搜狗站长工具
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Sogou
{
    /**
     * 获取搜狗 SR
     * @param string $domain
     * @return string|int
     */
    public static function getRank($url)
    {
        $http = new HttpProClient();
        $http->setBaseUri('http://rank.ie.sogou.com');
        /** @var HttpResponse $response */
        $response = $http->get("sogourank.php", [
            'ur' => $url
        ]);
        if ($response->isOk()) {
            return str_replace(['sogourank=', "\r", "\n"], '', $response->getContent());
        } else {
            return 1;
        }
    }

    /**
     * 检查是否收录页面
     * @param string $url
     * @return bool
     */
    public static function checkInclude($url)
    {
        $client = new HttpProClient();
        $client->setHttpOptions([
            'http_errors' => false,
        ]);
        $client->setBaseUri('https://www.sogou.com');
        $response = $client->request('get', "/web?query={$url}", [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'
            ],
        ]);
        if (!strpos($response->getContent(), '点击此处提交')) {
            return true;
        } else {
            return false;
        }
    }
}