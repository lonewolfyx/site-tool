<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\Site\Tool;

use Larva\Supports\HttpProClient;
use Larva\Supports\HttpResponse;

/**
 * 爱站API
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Aizhan
{
    /**
     * 获取爱站百度-网站权重
     * @param string $key
     * @param string|array $domains
     * @return array
     */
    public static function BaiduRank($key, $domains)
    {
        $client = new HttpProClient();
        $client->setHttpOptions([
            'http_errors' => false,
        ]);
        $client->setBaseUri('https://apistore.aizhan.com');
        if (is_array($domains)) {
            $domains = implode('|', $domains);
        }
        /** @var HttpResponse $response */
        $response = $client->post("/baidurank/siteinfos/" . $key, [
            'domains' => $domains
        ]);
        return $response->getData();
    }

}