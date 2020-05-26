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
        'domain' => env('USER_CENTER_HOST', 'http://gyk.ucenter_service.debug.xthktech.cn'), //请求的域名
        'app_id' => env('UC_APP_ID', 2), //应用ID
        'app_secret' => env('UC_APP_SECRET', '3ff1eff4a033ca786610497fccfa5f44'), //应用秘钥
        'source' => env('UC_SOURCE', 2), //来源
        'app_name' => env('APP_NAME_USER_CENTER', 'web') //应用名称
    ],

    'params' => [
        'mobile' => 'phone',
        'password' => 'password',
        'password_confirmation' => 'password_confirmation',
        'original_password' => 'original_password',
        'sms_code' => 'sms_code',
        'sms_type' => 'sms_type',
        'register_ip' => 'register_ip',
        'login_ip' => 'login_ip',
        'push_id' => 'push_id',
        'push_type' => 'push_type',
        'open_id' => 'open_id',
        'wechat_type' => 'wechat_type',
        'status' => 'status',
        'city_id' => 'city_id',
        'real_name' => 'name',
        'gender' => 'gender',
        'birthday' => 'birthday',
        'emergency_mobile' => 'emergency_phone',
        'user_id' => 'uc_user_id',
        'student_id' => 'uc_student_id'
    ],

    'model' => [
        'CityModel' => \App\Models\Base\CityModel::class,
        'UserCenterLogModel' => \App\Models\Mongodb\UserCenterLogsModel::class,
        'StudentModel' =>  \App\Models\Student\StudentModel::class,
    ],

     //todo 暂时未使用异步方式
    'nsq' => [
        'topic' => 'xthk_offline_user',
        'student_update' => '',
    ]
];

```
* 同步调用
```$xslt
注入OfflineUser类，调用里面对应的方法
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
