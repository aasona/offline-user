<h1 align="center"> offline-user </h1>

<p align="center"> a ucenter for offline sdk.</p>


## Installing

```shell
$ composer require ucenter/offline-user -vvv
```

## Usage

TODO
* 追加配置文件 config/ucenter.php
```$xslt
<?php
return [
    'config' => [
        'domain' => env('UCENTER_HOST', 'http://127.0.0.1'), //请求的域名
        'app_id' => env('APP_ID', 1), //应用ID
        'app_secret' => env('APP_SECRET', '123456'), //应用秘钥
        'source' => env('SOURCE', 1), //来源
        'app_name' => env('APP_NAME', 'web') //应用名称
    ],

    'params' => [
        'mobile',
        'password',
        'password_confirmation',
        'original_password',
        'sms_code',
        'sms_type',
        'register_ip',
        'login_ip',
        'push_id',
        'push_type',
        'open_id',
        'wechat_type',
        'user_id',
        'status',
        'city_id',
        'real_name',
        'gender',
        'birthday',
        'emergency_mobile',
        'student_id'
    ],

 'model' => [
         'CityModel' => \App\Models\Base\CityModel::class,
         'UserCenterLogModel' => \App\Models\Mongodb\UserCenterLogsModel::class
     ],
];

```
* 同步调用
```$xslt

```
* 异步调用
```$xslt

```
## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/ucenter/offline-user/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/ucenter/offline-user/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT
