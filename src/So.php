<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\Site\Tool;

use Larva\Supports\HttpClient;
use Larva\Supports\HttpProClient;
use Larva\Supports\HttpResponse;

/**
 * 360 搜索自动推送
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class So
{
    /**
     * 360 搜索推送
     * @param string $url
     * @param string $sid
     * @return mixed
     */
    public static function push($url, $sid)
    {
        $token = static::token($url, $sid);
        $client = new HttpClient();
        $client->setHttpOptions([
            'http_errors' => false,
        ]);
        $client->setBaseUri('https://s.360.cn');
        $url = urlencode($url);
        return $client->request('get', "/so/zz.gif?url={$url}&sid={$sid}&token={$token}", [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'
            ],
        ]);
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
        $client->setBaseUri('https://www.so.com');
        $response = $client->request('get', "/s?ie=utf-8&q={$url}", [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'
            ],
        ]);
        if (!strpos($response->getContent(), '找不到该UR')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 生成令牌
     * @param $url
     * @param $sid
     * @return string
     */
    public static function token($url, $sid)
    {
        $n = array_reverse(str_split($url));
        $r = str_split($sid);
        $i = [];
        for ($s = 0; $s < 16; $s++) {
            $i[] = $r[$s] . ($n[$s] ?? "");
        }
        return implode('', $i);
    }

    /**
     * 获取推荐搜索
     * @param string $word
     * @return array|false
     */
    public static function suggestion($word)
    {
        $http = new HttpProClient();
        $http->setBaseUri('https://sug.so.360.cn/');
        /** @var HttpResponse $response */
        $response = $http->get("suggest", [
            'word' => $word,
        ], [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'
            ],
        ]);
        if ($response->isOk()) {
            $words = json_decode($response->getContent(),true);
            $ret = [];
            foreach ($words['result'] as $word) {
                $ret[] = $word['word'];
            }
            return $ret;
        } else {
            return false;
        }
    }
}