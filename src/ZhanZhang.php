<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Site\Tool;

use Larva\Supports\HttpProClient;
use Larva\Supports\HttpResponse;
use Larva\Supports\Json;

/**
 * 站长工具
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class ZhanZhang
{
    /**
     * 获取站长资源网 自动友联
     * @param string $sign 签名
     * @param string $id 站点ID
     * @return array|false
     */
    public static function autoLink($sign, $id)
    {
        $http = new HttpProClient();
        $http->setBaseUri('http://auto.link.2898.com/index/Autochain/AutoChainYL');
        /** @var HttpResponse $response */
        $response = $http->get("index/Autochain/AutoChainYL", [
            'sign' => $sign,
            'id' => $id,
            'dtype' => 'json',
            'text' => 'true',
            'code' => 'utf-8'
        ]);
        if ($response->isOk()) {
            return Json::decode($response->getContent());
        } else {
            return false;
        }
    }
}