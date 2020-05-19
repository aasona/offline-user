<?php
/**
 * User: yuanHb <1214846385@qq.com>
 * Date: 2020/5/19
 * Time: 10:09.
 */


namespace Xthk\Ucenter\offline\Facades;


class UserCenter extends \Illuminate\Support\Facades\Facade
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
