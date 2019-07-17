<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Site\Tool;


use Larva\Supports\HttpClient;

/**
 * 备案
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Beian
{
    public static function get($hostname)
    {
        $resolve = Domain::Resolve($hostname);
        $domain = $resolve->getRegistrableDomain();

        $info = static::getForSojson($domain);
        return $info;

    }


    /**
     * 备案查询
     * @param string $domain
     * @return array|false
     *
     * {
    "nature": "个人",			//备案性质，个人 or 企业
    "icp": "京ICP备13051813号",		//主备案号
    "indexUrl": "www.sojson.com",	//首页
    "sitename": "我们的JSON",		//备案的名称
    "domain": " sojson.com ",		//备案的一级域名
    "nowIcp": "湘ICP备19009812号-2",	//当前域名的备案号
    "type": 200,			//备案查询状态
    "search": "sojson.com",		//你查询的信息
    "checkDate": "2013-12-16"		//备案信息检查时间
    "name": "杜占召"			//个人是备案人名称，公司是公司名称
    }
     */
    public static function getForSojson($domain)
    {
        $client = new HttpClient();
        $client->setHttpOptions([
            'http_errors' => false,
        ]);
        $client->setBaseUri('https://www.sojson.com');
        $response = $client->get('/api/beian/' . $domain);
        if (is_array($response) && $response['type'] == 200) {
            return [
                'nature' => $response['nature'],
                'icp' => $response['icp'],
                'indexUrl' => $response['indexUrl'],
                'sitename' => $response['sitename'],
                'domain' => $response['domain'],
                'nowIcp' => $response['nowIcp'],
                'checkDate' => $response['checkDate'],
                'name' => $response['name'],
            ];
        }
        return false;
    }
}