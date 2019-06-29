<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
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
     * @return mixed
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
        return $client->request('post', 'urls', [
            'query' => ['site' => $site, 'token' => $token],
            'body' => $urls
        ]);
    }

    /**
     * 链接更新
     * @param string $site 网站
     * @param string $token Token
     * @param string|array $urls Url列表
     * @return mixed
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
        return $client->request('post', 'update', [
            'query' => ['site' => $site, 'token' => $token],
            'body' => $urls
        ]);
    }

    /**
     * 链接删除
     * @param string $site 网站
     * @param string $token Token
     * @param string|array $urls Url列表
     * @return mixed
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
        return $client->request('post', 'del', [
            'query' => ['site' => $site, 'token' => $token],
            'body' => $urls
        ]);
    }

    /**
     * MIP 提交
     * @param string $site
     * @param string $token
     * @param string|array $urls
     * @return mixed
     */
    public static function MIPPush($site, $token, $urls)
    {
        if (is_array($urls)) {
            $urls = implode("\n", $urls);
        }
        $client = new HttpClient();
        $client->setHttpOptions([
            'http_errors' => false,
        ]);
        $client->setBaseUri('http://data.zz.baidu.com');
        return $client->request('post', 'urls', [
            'query' => ['site' => $site, 'token' => $token, 'type' => 'mip'],
            'body' => $urls
        ]);
    }

    /**
     * AMP 提交
     * @param string $site
     * @param string $token
     * @param string|array $urls
     * @return mixed
     */
    public static function AMPPush($site, $token, $urls)
    {
        if (is_array($urls)) {
            $urls = implode("\n", $urls);
        }
        $client = new HttpClient();
        $client->setHttpOptions([
            'http_errors' => false,
        ]);
        $client->setBaseUri('http://data.zz.baidu.com');
        return $client->request('post', 'urls', [
            'query' => ['site' => $site, 'token' => $token, 'type' => 'amp'],
            'body' => $urls
        ]);
    }

    /**
     * AMP MIP 清理
     * @param string $token Token
     * @param string $url Url
     * @return mixed
     */
    public static function AMPClean($token, $url)
    {
        $endpoint = '/update-ping/c/' . urlencode($url);
        $client = new HttpClient();
        $client->setHttpOptions([
            'http_errors' => false,
        ]);
        $client->setBaseUri('http://c.mipcdn.com');
        return $client->post($endpoint, ['key' => $token]);
    }

    /**
     * 天级收录
     * @param string $appId AppID
     * @param string $token Token
     * @param string|array $urls Url列表
     * @return HttpResponse
     */
    public static function DayInclusion($appId, $token, $urls)
    {
        if (is_array($urls)) {
            $urls = implode("\n", $urls);
        }
        $client = new HttpProClient();
        $client->setHttpOptions([
            'http_errors' => false,
        ]);
        $client->setBaseUri('http://data.zz.baidu.com');
        return $client->request('post', 'urls', [
            'query' => ['appid' => $appId, 'token' => $token, 'type' => 'batch'],
            'body' => $urls
        ]);
    }

    /**
     * 周级收录
     * @param string $appid AppID
     * @param string $token Token
     * @param string|array $urls Url列表
     * @return HttpResponse
     */
    public static function WeekInclusion($appid, $token, $urls)
    {
        if (is_array($urls)) {
            $urls = implode("\n", $urls);
        }
        $client = new HttpProClient();
        $client->setHttpOptions([
            'http_errors' => false,
        ]);
        $client->setBaseUri('http://data.zz.baidu.com');
        return $client->request('post', 'urls', [
            'query' => ['appid' => $appid, 'token' => $token, 'type' => 'batch'],
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
}