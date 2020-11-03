<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\Site\Tool;

use Larva\Supports\HttpClient;
use Larva\Supports\HttpProClient;
use Larva\Supports\HttpResponse;

/**
 * Class Bing
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Bing
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
        $client = new HttpClient();
        $client->timeout = 10.0;
        $client->connectTimeout = 10.0;
        $client->setHttpOptions([
            'http_errors' => false,
        ]);
        $client->setBaseUri('https://ssl.bing.com');
        if (is_array($urls)) {
            return $client->postJSON("/webmaster/api.svc/json/SubmitUrlbatch?apikey={$token}", ['siteUrl' => $site, 'urlList' => $urls]);
        } else {
            return $client->postJSON("/webmaster/api.svc/json/SubmitUrl?apikey={$token}", ['siteUrl' => $site, 'url' => $urls]);
        }
    }

    /**
     * 获取剩余配额
     * @param string $site
     * @param string $token
     * @return array
     */
    public static function GetUrlSubmissionQuota($site, $token)
    {
        $client = new HttpClient();
        $client->timeout = 10.0;
        $client->connectTimeout = 10.0;
        $client->setHttpOptions([
            'http_errors' => false,
        ]);
        $client->setBaseUri('https://ssl.bing.com');
        return $client->get("/webmaster/api.svc/json/GetUrlSubmissionQuota", ['siteUrl' => $site, 'apikey' => $token]);
    }

    /**
     * 获取推荐搜索
     * @param string $word
     * @return array|false
     */
    public static function suggestion($word)
    {
        $http = new HttpProClient();
        $http->setBaseUri('https://sg1.api.bing.com/');
        /** @var HttpResponse $response */
        $response = $http->get("qsonhs.aspx", [
            'q' => $word,
            'type' => 'json',
        ], [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'
            ],
        ]);
        if ($response->isOk()) {
            $ret = [];
            $arr = json_decode($response->getContent(), true);
            if (isset($arr['AS']['Results'][0]['Suggests'])) {
                foreach ($arr['AS']['Results'][0]['Suggests'] as $result) {
                    $ret[] = $result['Txt'];
                }
            }
            return $ret;
        } else {
            return false;
        }
    }
}