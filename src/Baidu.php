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
 * 百度站长工具
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Baidu
{
    /**
     * 链接提交
     * @param string $site 网站
     * @param string $token Token
     * @param string|array $urls Url列表
     * @return array
     */
    public static function Push($site, $token, $urls)
    {
        if (is_array($urls)) {
            $urls = implode("\n", $urls);
        }
        $client = new HttpClient();
        $client->setHttpOptions([
            'http_errors' => false,
        ]);
        $client->setBaseUri('http://data.zz.baidu.com');
        return $client->request('post', "/urls?site={$site}&token={$token}", [
            'body' => $urls
        ]);
    }

    /**
     * 链接更新
     * @param string $site 网站
     * @param string $token Token
     * @param string|array $urls Url列表
     * @return array
     */
    public static function Update($site, $token, $urls)
    {
        if (is_array($urls)) {
            $urls = implode("\n", $urls);
        }
        $client = new HttpClient();
        $client->setHttpOptions([
            'http_errors' => false,
        ]);
        $client->setBaseUri('http://data.zz.baidu.com');
        return $client->request('post', "/update?site={$site}&token={$token}", [
            'body' => $urls
        ]);
    }

    /**
     * 链接删除
     * @param string $site 网站
     * @param string $token Token
     * @param string|array $urls Url列表
     * @return array
     */
    public static function Delete($site, $token, $urls)
    {
        if (is_array($urls)) {
            $urls = implode("\n", $urls);
        }
        $client = new HttpClient();
        $client->setHttpOptions([
            'http_errors' => false,
        ]);
        $client->setBaseUri('http://data.zz.baidu.com');
        return $client->request('post', "/del?site={$site}&token={$token}", [
            'body' => $urls
        ]);
    }

    /**
     * 快速收录
     * @param string $site 网站
     * @param string $token Token
     * @param string|array $urls Url列表
     * @return HttpResponse
     */
    public static function DailyPush($site, $token, $urls)
    {
        if (is_array($urls)) {
            $urls = implode("\n", $urls);
        }
        $client = new HttpClient();
        $client->setHttpOptions([
            'http_errors' => false,
        ]);
        $client->setBaseUri('http://data.zz.baidu.com');
        return $client->request('post', "/urls?site={$site}&token={$token}&type=daily", [
            'body' => $urls
        ]);
    }

    /**
     * 蜘蛛模拟
     * @param string $url
     * @return string|false
     */
    public static function SpiderPC($url)
    {
        if (strpos($url, "://") == false) {
            $url = "http://" . $url;
        }
        $client = new HttpProClient();
        $client->setHttpOptions([
            'http_errors' => false,
        ]);
        $client->setBaseUri($url);
        /** @var HttpResponse $response */
        $response = $client->get('', [], [
            'User-Agent' => 'Mozilla/5.0 (compatible; Baiduspider/2.0;+http://www.baidu.com/search/spider.html）'
        ]);
        if ($response->isOk()) {
            return $response->getContent();
        }
        return false;
    }

    /**
     * 蜘蛛模拟
     * @param string $url
     * @return string|false
     */
    public static function SpiderMobile($url)
    {
        if (strpos($url, "://") == false) {
            $url = "http://" . $url;
        }
        $client = new HttpProClient();
        $client->setHttpOptions([
            'http_errors' => false,
        ]);
        $client->setBaseUri($url);
        /** @var HttpResponse $response */
        $response = $client->get('', [], [
            'User-Agent' => 'Mozilla/5.0 (Linux;u;Android 4.2.2;zh-cn;) AppleWebKit/534.46 (KHTML,likeGecko) Version/5.1 Mobile Safari/10600.6.3 (compatible; Baiduspider/2.0;+http://www.baidu.com/search/spider.html)'
        ]);
        if ($response->isOk()) {
            return $response->getContent();
        }
        return false;
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
        $client->setBaseUri('https://www.baidu.com');
        $response = $client->get("/s?wd={$url}", [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'
            ],
        ]);
        if (!strpos($response->getContent(), '提交网址')) {
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
        $http->setBaseUri('http://suggestion.baidu.com/');
        /** @var HttpResponse $response */
        $response = $http->get("su", [
            'wd' => $word,
            //'p' => '1',
            'cb' => 'window.bdsug.sug'
        ], [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'
            ],
        ]);
        if ($response->isOk()) {
            $content = str_replace(['window.bdsug.sug(', ');'], '', mb_convert_encoding($response->getContent(), "UTF-8", "GB2312"));
            $arr = static::ext_json_decode($content,true);
            return $arr['s'];
        } else {
            return false;
        }
    }

    /**
     * 兼容key没有双引括起来的JSON字符串解析
     * @param string $json JSON字符串
     * @param boolean $assoc true:Array,false:Object
     * @return array/object
     */
    private static function ext_json_decode($json, $assoc = true)
    {
        if (preg_match('/\w:/', $json)) {
            $json = preg_replace('/(\w+):/is', '"$1":', $json);
        }
        return json_decode($json, $assoc);
    }
}