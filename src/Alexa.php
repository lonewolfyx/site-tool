<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\Site\Tool;

use Larva\Supports\HttpClient;

/**
 * Class Alexa
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Alexa
{
    /**
     * 获取 Alexa 排名
     * @param string $domain
     * @return array|bool
     */
    public static function getRank($domain)
    {
        $http = new HttpClient();
        $http->setBaseUri('http://data.alexa.com');
        try {
            $response = $http->get("/data", [
                'cli' => 10,
                'url' => $domain
            ]);
            $alexa = [];
            if (isset($response['SD']['COUNTRY']['@attributes']['RANK']) && $response['SD']['COUNTRY']['@attributes']['NAME'] == 'China') {
                $alexa['china_rank'] = $response['SD']['COUNTRY']['@attributes']['RANK'];
            }
            if (isset($response['SD']['POPULARITY']['@attributes']['TEXT'])) {
                $alexa['global_rank'] = $response['SD']['POPULARITY']['@attributes']['TEXT'];
            }
            return $alexa;
        } catch (\Exception $exception) {
            return false;
        }
    }
}