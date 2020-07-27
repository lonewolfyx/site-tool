<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\Site\Tool;

use Larva\Supports\HttpClient;

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
        if (!is_array($urls)) {
            $urls = [$urls];
        }
        $client = new HttpClient();
        $client->setHttpOptions([
            'http_errors' => false,
        ]);
        $client->setBaseUri('https://ssl.bing.com');
        return $client->postJSON("/webmaster/api.svc/json/SubmitUrlbatch?apikey={$token}",['siteUrl'=>$site,'urlList'=>$urls]);
    }
}