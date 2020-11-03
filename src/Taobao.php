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
 * Class Taobao
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Taobao
{
    /**
     * 获取推荐搜索
     * @param string $word
     * @return array|false
     */
    public static function suggestion($word)
    {
        $http = new HttpProClient();
        $http->setBaseUri('https://suggest.taobao.com');
        /** @var HttpResponse $response */
        $response = $http->get("sug", [
            'q' => $word,
            'code' => 'utf-8',
        ], [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'
            ],
        ]);
        if ($response->isOk()) {
            $words = json_decode($response->getContent(), true);
            $ret = [];
            foreach ($words['result'] as $word) {
                $ret[] = $word[0];
            }
            return $ret;
        } else {
            return false;
        }
    }
}