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

    /**
     * 获取推荐搜索
     * @param string $word
     * @return array|false
     */
    public static function suggestion($word)
    {
        $http = new HttpProClient();
        $http->setBaseUri('http://w.sugg.sogou.com');
        /** @var HttpResponse $response */
        $response = $http->get("sugg/ajaj_json.jsp", [
            'key' => $word,
            'type' => 'web'
        ], [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'
            ],
        ]);
        if ($response->isOk()) {
            $content = str_replace(['window.sogou.sug(', ",-1);"], '', mb_convert_encoding($response->getContent(), "UTF-8", "GB2312"));
            $arr = json_decode($content, true);
            return $arr[1];
        } else {
            return false;
        }
    }
}