<?php
/**
 * User: yuanHb <1214846385@qq.com>
 * Date: 2020/4/27
 * Time: 17:29.
 */


namespace Xthk\Ucenter\offline\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\Cache\CacheManager
 * @see \Illuminate\Cache\Repository
 */
class OfflineUser extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'offlineUser';
    }
}
