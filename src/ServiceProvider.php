<?php
/**
 * User: yuanHb <1214846385@qq.com>
 * Date: 2020/3/12
 * Time: 15:58.
 */

namespace Xthk\Ucenter\offline;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton('offlineUser', function () {
            return new OfflineUser(config('ucenter.config'));
        });

        $this->app->alias(OfflineUser::class, 'offlineUser');
    }

    public function provides()
    {
        return [OfflineUser::class, 'offlineUser'];
    }
}
