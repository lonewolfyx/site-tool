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
 * Class Google
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Google
{

    /**
     * 获取 Google PR
     * @param string $domain
     * @return int
     */
    public static function PR($domain)
    {
        $http = new HttpProClient();
        $http->setBaseUri('http://pr.aizhan.com');
        /** @var HttpResponse $response */
        $response = $http->get("/{$domain}");
        if ($response->isOk()) {
            if (preg_match("/\/pr\/pr(.*).gif/U", $response->getContent(), $match)) {
                return intval($match [1]);
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}