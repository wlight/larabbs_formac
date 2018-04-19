<?php

namespace App\Handlers;

use Overtrue\Pinyin\Pinyin;
use GuzzleHttp\Client;

class SlugTranslateHandler
{
    public function translate($text)
    {
        // 实例化 HTTP 客户端
        $http = new Client;

        // 初始化配置信息
        $api = 'http://api.fanyi.baidu.com/api/trans/vip/translate?';
        $apiid = config('services.baidu_translate.appid');
        $key = config('services.baidu_translate.key');
        $salt = time();

        // 如果没有配置百度翻译，自动使用兼容的拼音方案
        if (empty($apiid) || empty($key))
        {
            return $this->pinyin($text);
        }
        // 根据文档，生成 sign
        $sign = md5($apiid.$text.$salt.$key);

        // 构建请求参数
        $query = http_build_query([
            "q" => $text,
            "from" => "zh",
            "to" => "en",
            "appid" => $apiid,
            "salt" => $salt,
            "sign" => $sign,
        ]);
        // 发送 HTTP Get 请求
        $response = $http->get($api.$query);

        $result = json_decode($response->getBody(), true);
        // dd($result);

        // 尝试获取翻译结果
        if(isset($result['trans_result'][0]['dst'])){
            return str_slug($result['trans_result'][0]['dst']);
        } else {
            // 如果百度翻译没有结果，使用拼音作为后备计划。
            return $this->pinyin($text);
        }
    }

    public function pinyin($text)
    {
        return str_slug(app(Pinyin::class)->permalink($text));
    }
}