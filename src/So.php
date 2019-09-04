<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Site\Tool;

use Larva\Supports\HttpClient;

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
     * 生成令牌
     * @param $url
     * @param $sid
     * @return string
     */
    protected static function token($url, $sid)
    {
        $n = array_reverse(str_split($url));
        $r = str_split($sid);
        $i = [];
        for ($s = 0; $s < 16; $s++) {
            $i[] = $r[$s] . ($n[$s] ?? "");
        }
        return implode('', $i);
    }
}