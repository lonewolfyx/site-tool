<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
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
     * @return mixed|number
     */
    public static function getBr($domain)
    {
        $http = new HttpProClient();
        $http->setBaseUri('http://rank.ie.sogou.com');
        /** @var HttpResponse $response */
        $response = $http->get("sogourank.php", [
            'ur' => "http://{$domain}/"
        ]);
        if ($response->isOk()) {
            return str_replace(['sogourank=', "\r", "\n"], '', $response->getContent());
        } else {
            return $response->getContent();
        }
        return 1;
    }
}