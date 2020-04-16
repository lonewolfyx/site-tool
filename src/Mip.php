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
    public static function replace( $content ) {
        $content = self::style( $content );
        $content = self::img( $content );
        $content = self::script( $content );
        $content = self::forbidden( $content );
        $content = self::convert( $content );
        return $content;
    }

    /**
     * 编码转换
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

    /**
     * 处理样式表
     * @param string $content
     * @return string|null
     */
    public static function style( $content ) {
        preg_match_all( '/ style=\".*?\"/', $content, $style );
        if ( ! is_null( $style ) ) {
            foreach ( $style[0] as $index => $value ) {
                $mip_style = preg_replace( '/ style=\".*?\"/', '', $style[0][ $index ] );
                $content   = str_replace( $style[0][ $index ], $mip_style, $content );
            }
        }
        $content = preg_replace( "/<(style.*?)>(.*?)<(\/style.*?)>/si", "", $content );
        $content = preg_replace( "/<(\/?style.*?)>/si", "", $content );
        return $content;
    }

    /**
     * 处理图片
     * @param string $content
     * @return string
     */
    public static function img( $content ) {
        preg_match_all( '/<img (.*?)\>/', $content, $images );
        if ( ! is_null( $images ) ) {
            foreach ( $images[1] as $index => $value ) {
                $mip_img = str_replace( '<img', '<mip-img', $images[0][ $index ] );
                $mip_img = str_replace( '>', '></mip-img>', $mip_img );
                $mip_img = preg_replace( '/(width|height)="\d*"\s/', '', $mip_img );
                $mip_img = preg_replace( '/ srcset=\".*?\"/', '', $mip_img );
                $content = str_replace( $images[0][ $index ], $mip_img, $content );
            }
        }
        return $content;
    }

    /**
     * 搞掉JS 脚本
     * @param string $content
     * @return string|null
     */
    public static function script( $content ) {
        $content = preg_replace( "/<(script.*?)>(.*?)<(\/script.*?)>/si", "", $content );
        $content = preg_replace( "/<(\/?script.*?)>/si", "", $content );
        return $content;
    }

    /**
     * 搞掉不支持的标签
     * @param string $content
     * @return string|null
     */
    public static function forbidden( $content ) {
        $forbidden_html = array( "frame", "param", "form", "input", "textarea", "select", "option" );
        for ( $i = 0; $i < sizeof( $forbidden_html ); $i ++ ) {
            $content = preg_replace( "/<" . $forbidden_html[ $i ] . "[^>]*>(.*?)<\/" . $forbidden_html[ $i ] . ">/is", "", $content );
        }
        return $content;
    }
}