<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\Site\Tool;

/**
 * Mip 内容助手
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Mip
{
    /**
     * MIP内容替换
     * @param string $content
     * @return string
     */
    public static function replace($content = '')
    {
        $pattern1 = "#<img.*?src=['\"](.*?)['\"].*?>#ims";
        $imgContent = [];
        preg_match_all($pattern1, $content, $img);
        $imgContent = $img[0];
        $imgUrl = $img[1];
        $mipImg = [];
        foreach ($imgContent as $key => $val) {
            $temp = str_replace('<img', 'mip-img', $val);
            $temp = str_replace('/>', '></mip-img', $temp);
            $url = $imgUrl[$key];
            $temp = preg_replace("/src=['\"].*?['\"]/si", "src=\"$url\"", $temp);
            $mipImg[$key] = $temp;
        }
        $content = preg_replace($imgContent, $mipImg, $content);
        $content = preg_replace("/<a /si", "<a target=\"_blank\" ", $content);
        $content = preg_replace("/style=\".*?\"/si", "", $content);
        return static::convert($content);
    }

    /**
     * MIP编码转换
     * @param string $string
     * @return string
     */
    public static function convert($string)
    {
        $fileType = mb_detect_encoding($string, ['UTF-8', 'GBK', 'LATIN1', 'BIG5']);
        if ($fileType != 'UTF-8') {
            $string = mb_convert_encoding($string, 'utf-8', $fileType);
        }
        return $string;
    }
}