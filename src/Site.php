<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Site\Tool;

use Larva\Supports\HtmlHelper;
use Larva\Supports\HttpClient;
use Larva\Supports\HttpProClient;
use Larva\Supports\HttpResponse;
use Larva\Supports\IPHelper;

/**
 * 站点工具
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Site
{
    /**
     * 获取站点信息
     * @param string $hostname
     * @return array|bool
     */
    public static function getInfo($hostname)
    {
        $info = [];
        try {
            //解析IP
            $ip = IPHelper::dnsRecord($hostname, DNS_A, true);
            if ($ip && is_array($ip)) {
                $info['ip'] = array_shift($ip);
            } else {
                return false;
            }
        } catch (\Exception $exception) {

        }
        try {
            $response = static::getResponse("https://" . $hostname);
            if ($response && $response->isOk()) {
                $info['https'] = true;
            } else {
                $response = static::getResponse("http://" . $hostname);
                if ($response && $response->isOk()) {
                    $info['https'] = false;
                }
            }
            $heads = HtmlHelper::getHeadTags($response->getContent());
            if (isset($heads['title'])) {
                $info['title'] = $heads['title'];
            }
            if (isset($heads['metaTags']['description'])) {
                $info['description'] = $heads['metaTags']['description'];
            }
            if (isset($heads['metaTags']['keywords'])) {
                $info['keyword'] = $heads['metaTags']['keywords'];
            }
            $links = HtmlHelper::getHtmlOutLink($response->getContent(), $hostname);
            $info['outLinks'] = $links['dataList'];
            return $info;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * 获取网站缩略图
     * @param string $url
     * @return array
     * @doc https://blinky.nemui.org/
     */
    public static function getShot($url)
    {
        return [
            '64' => 'https://blinky.nemui.org/shot/small?' . $url,
            '128' => 'https://blinky.nemui.org/shot?' . $url,
            '256' => 'https://blinky.nemui.org/shot/large?' . $url,
            '512' => 'https://blinky.nemui.org/shot/xlarge?' . $url,
        ];
    }

    /**
     * 获取 Header
     * @param string $url
     * @return string
     */
    public function getHeader($url)
    {
        if (strpos($url, "://") == false) {
            $url = "http://" . $url;
        }
        try {
            $headers = HttpClient::getHeaders($url);
            $ret = '';
            foreach ($headers as $name => $values) {
                $ret .= $name . ": " . implode(", ", $values) . PHP_EOL;
            }
            return $ret;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * 获取页面内容
     * @param string $hostname
     * @return HttpResponse|false
     */
    protected static function getResponse($hostname)
    {
        $http = new HttpProClient();
        $http->setHttpOptions(['allow_redirects' => false, 'verify' => false]);//禁止跳转
        try {
            /** @var HttpResponse $response */
            $response = $http->get($hostname);
            if ($response && $response->isOk()) {
                return $response;
            }
        } catch (\Exception $exception) {

        }
        return false;
    }
}