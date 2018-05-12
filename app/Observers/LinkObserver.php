<?php
/**
 * Created by PhpStorm.
 * User: wangliang
 * Date: 2018/5/12
 * Time: 上午9:58
 */

namespace App\Observers;

use App\Models\Link;
use Cache;

class LinkObserver
{
    // 在保存的时候清空 cache_key 对应的缓存
    public function saved(Link $link)
    {
        Cache::forget($link->cache_key);
    }
}