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
}