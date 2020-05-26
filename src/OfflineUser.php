<?php
/**
 * User: yuanHb <1214846385@qq.com>
 * Date: 2020/4/24
 * Time: 10:50.
 */

namespace Xthk\Ucenter\offline;

use Xthk\Ucenter\offline\Exceptions\HttpException;
use Xthk\Ucenter\offline\Exceptions\InvalidArgumentException;

class OfflineUser
{

    protected $connect;

    public function __construct(array $config)
    {

        $this->connect = new UserCenterConnect($config);
    }

    /**
     * 注册
     * @param $params
     * @param  string  $type
     * @return string
     * @throws Exceptions\Exception
     * @throws HttpException
     * @throws InvalidArgumentException
     * @author:yuanHb  2020/4/26 10:36
     */
    public function registerByUserCenter($params, $type = 'app')
    {
        if (!\in_array(\strtolower($type), ['app', 'admin'])) {
            throw new InvalidArgumentException('Invalid response format: '.$type);
        }
        #请求地址设置
        switch ($type) {
            case 'app' : //app端登录需要验证码
                $this->connect->setRequestUri('/api/user'.\Xthk\Ucenter\UriConfig::USER_REGISTER);
                break;
            case 'admin': //后台登录不需要验证码
                $this->connect->setRequestUri('/api/user'.\Xthk\Ucenter\UriConfig::USER_REGISTER);
                break;
        }
        #参数设置
        $this->connect->setInput($params);
        $this->connect->setClientIp();
        $this->connect->setRegisterIp($this->connect->getClientIp());
        try {
            return $this->connect->response($this->connect->send());
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }


    /**
     * 登录
     * @param $params
     * @param $type
     * @return string
     * @throws Exceptions\InvalidArgumentException
     * @throws HttpException
     * @author:yuanHb  2020/4/26 15:36
     */
    public function loginByUserCenter($params, $type)
    {
        if (!\in_array(\strtolower($type), ['pwd', 'code', 'phone'])) {
            throw new InvalidArgumentException('Invalid response format: '.$type);
        }
        #请求地址设置
        switch ($type) {
            case 'pwd' : //通过密码登录
                $this->connect->setRequestUri('/api/user'.\Xthk\Ucenter\UriConfig::USER_LOGIN_BY_PWD);
                break;
            case 'code': //通过短信验证码登录
                $this->connect->setRequestUri('/api/user'.\Xthk\Ucenter\UriConfig::USER_LOGIN_BY_MOBILE);
                break;
            case 'phone': //通过手机号直接登录
                $this->connect->setRequestUri('/api/user/mobileLogin');
                break;
        }
        #参数设置
        $this->connect->setInput($params);
        $this->connect->setClientIp();
        $this->connect->setLoginIp($this->connect->getClientIp());
        #发送请求
        try {
            return $this->connect->response($this->connect->send());
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }

    }

    /**
     * 登出
     * @param $params
     * @return string
     * @throws HttpException
     * @throws Exceptions\InvalidArgumentException
     * @author:yuanHb  2020/4/26 15:36
     */
    public function logoutByUserCenter($params)
    {
        $this->connect->setRequestUri('/api/user'.\Xthk\Ucenter\UriConfig::USER_LOGOUT);
        #参数设置
        $this->connect->setInput($params);
        #发送请求
        try {
            return $this->connect->response($this->connect->send());
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }


    /**
     * 重置密码
     * @param $params
     * @return string
     * @throws HttpException
     * @throws InvalidArgumentException
     * @author:yuanHb  2020/4/27 17:32
     */
    public function resetPwdForUserCenter($params)
    {
        $this->connect->setRequestUri('/api/user'.\Xthk\Ucenter\UriConfig::RESET_PWD);
        $this->connect->setInput($params);
        try {
            return $this->connect->response($this->connect->send());
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }


    /**
     * 通过手机号查询用户信息
     * @param $params
     * @return array|bool
     * @throws HttpException
     * @throws InvalidArgumentException
     * @author:yuanHb  2020/4/30 17:33
     */
    public function getUserByUserCenter($params)
    {
        if (isset($params['uc_user_id']) || isset($params['user_id'])) {
            $userId = isset($params['uc_user_id']) ? $params['uc_user_id'] : $params['user_id'];
            $this->connect->setRequestUri('/api/user/userinfo');
        } else {
            $this->connect->setRequestUri('/api/user'.\Xthk\Ucenter\UriConfig::USER_GET_USERINFO_BY_MOBILE);
        }
        $this->connect->setInput($params);
        if (isset($userId)) {
            $this->connect->setUserId($userId);
        }
        try {
            return $this->connect->response($this->connect->send());
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }


    /**
     * 查询学生
     * @param $params
     * @return string
     * @throws HttpException
     * @throws Exceptions\InvalidArgumentException
     * @author:yuanHb  2020/4/26 15:36
     */
    public function getStudentByUserCenter($params)
    {
        $this->connect->setRequestUri('/api/student'.\Xthk\Ucenter\UriConfig::STUDENT_GET);
        $this->connect->setInput($params);
        try {
            return $this->connect->response($this->connect->send());
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }

    }

    /**
     * 新增学生
     * @param $params
     * @return string
     * @throws HttpException
     * @throws Exceptions\Exception
     * @author:yuanHb  2020/4/26 15:36
     */
    public function userCenterCreateStudent($params)
    {
        $this->connect->setInput($params);
        if (isset($params['uc_user_id']) || isset($params['user_id'])) {
            $userId = isset($params['uc_user_id']) ? $params['uc_user_id'] : $params['user_id'];
        } else {
            $userId = $this->connect->getUserIdByStudentPhone();
        }
        if (!$userId) { //新增学生前这个电话号没有学生，走注册
            $this->connect->setRequestUri('/api/user/createUser');
            $this->connect->setClientIp();
        } else {
            $this->connect->setRequestUri('/api/student'.\Xthk\Ucenter\UriConfig::STUDENT_CREATE);
            $this->connect->setUserId($userId);
        }
        try {
            return $this->connect->response($this->connect->send());
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * 编辑学生
     * @param $params
     * @return string
     * @throws HttpException
     * @throws Exceptions\InvalidArgumentException
     * @author:yuanHb  2020/4/26 15:36
     */
    public function userCenterUpdateStudent($params)
    {
        if (isset($params['phone'])) {
            unset($params['phone']);
        }
        $this->connect->setRequestUri('/api/student'.\Xthk\Ucenter\UriConfig::STUDENT_UPDATE);
        $this->connect->setInput($params);
        try {
            if(empty($this->connect->getParmas())){
                return true;
            }
            return $this->connect->response($this->connect->send());
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }


    /**
     * 发送短信
     * @param $params
     * @return string
     * @throws HttpException
     * @throws InvalidArgumentException
     * @author:yuanHb  2020/4/27 19:10
     */
    public function sendSmsCodeByUserCenter($params)
    {
        $this->connect->setRequestUri('/api/user'.\Xthk\Ucenter\UriConfig::USER_SEND_SMS_CODE);
        $this->connect->setInput($params);
        try {
            return $this->connect->response($this->connect->send(), 'bool');
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }


    /**
     * 刷新token
     * @param $params
     * @return string
     * @throws HttpException
     * @throws InvalidArgumentException
     * @author:yuanHb  2020/4/27 19:54
     */
    public function refreshToken($params)
    {
        if (!isset($params['user_access_token'])) {
            throw new InvalidArgumentException('参数缺失');
        }
        if(isset($params['app_name'])){ //app name
            $this->connect->setAppName($params['app_name']);
            unset($params['app_name']);
        }
        $this->connect->setRequestUri('/api/user' . \Xthk\Ucenter\UriConfig::USER_REFRESH_TOKEN);
        $this->connect->setUserAccessToken($params['user_access_token']);
        try {
            return $this->connect->response($this->connect->send('', 'get'));
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * 判断用户是否已注册
     * @param $params
     * @return bool|mixed
     * @throws HttpException
     * @throws InvalidArgumentException
     * @author:yuanHb  2020/4/29 0:23
     */
    public function isRegisterByUserCenter($params)
    {
        $this->connect->setRequestUri('/api/userinfo'.\Xthk\Ucenter\UriConfig::USER_GET_USERINFO_BY_MOBILE);
        $this->connect->setInput($params);
        try {
            return $this->connect->response($this->connect->send(), 'bool');
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }


    /**
     * 设置默认学生
     * @param $params
     * @return array|bool
     * @throws HttpException
     * @throws InvalidArgumentException
     * @author:yuanHb  2020/4/30 11:01
     */
    public function setDefaultStudentForUserCenter($params)
    {
        $this->connect->setRequestUri('/api/student/setDefault');
        $this->connect->setInput($params);
        try {
            return $this->connect->response($this->connect->send(), 'bool');
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * 绑定openid或者pushid
     * @param $params
     * @return array|bool
     * @throws HttpException
     * @throws InvalidArgumentException
     * @author:yuanHb  2020/4/30 15:35
     */
    public function bindIdForUserCenter($params)
    {
        $this->connect->setRequestUri('/api/user/bindpush');
        $this->connect->setInput($params);
        try {
            return $this->connect->response($this->connect->send(), 'bool');
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }


    /**
     * 检查确认使用登录密码
     * @param $params
     * @return array // 0 未确认, 1 使用线上, 2 使用线下, 返回值为0才弹出，否则不弹
     * @throws HttpException
     * @throws InvalidArgumentException
     * @author:yuanHb  2020/5/14 13:43
     */
    public function checkConfirmPwd($params){

        $this->connect->setRequestUri('api/user/confirmPwd');
        $this->connect->setInput($params);
        try {
            return $this->connect->response($this->connect->send('', 'get'));
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }


    /**
     * 设置默认密码
     * @param $params
     * @return array|bool
     * @throws HttpException
     * @throws InvalidArgumentException
     * @author:yuanHb  2020/5/14 19:08
     */
    public function setDefaultPassword($params){
        $this->connect->setRequestUri('/api/user/setpasswd');
        $this->connect->setInput($params);
        try {
            return $this->connect->response($this->connect->send());
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }


    /**
     * 通过用户id 更改手机号
     * @param $params
     * @return array|bool
     * @throws HttpException
     * @throws InvalidArgumentException
     * @author:yuanHb  2020/5/14 19:10
     */
    public function changePhoneByUserId($params){
        $this->connect->setRequestUri('/api/userinfo/changeMobileById');
        $this->connect->setInput($params);
        try {
            return $this->connect->response($this->connect->send());
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * 旧密码修改密码
     * @param $params
     * @return bool|mixed
     * @throws HttpException
     * @throws InvalidArgumentException
     * @author:yuanHb  2020/4/29 0:23
     */
    public function changeByPwdByUserCenter($params)
    {
        $this->connect->setRequestUri('/api/userinfo'.\Xthk\Ucenter\UriConfig::CHANGE_BY_PWD);
        $this->connect->setInput($params);
        try {
            return $this->connect->response($this->connect->send());
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }


    /**
     * 验证短信验证码
     * @param $params
     * @return array|bool
     * @throws HttpException
     * @throws InvalidArgumentException
     * @author:yuanHb  2020/5/7 16:02
     */
    public function validateSmsCodeByUserCenter($params){
        $this->connect->setRequestUri('/api/user/smscode');
        $this->connect->setInput($params);
        try {
            return $this->connect->response($this->connect->send(), 'bool');
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * 拼接token
     * @param $userCenterToken
     * @param $appToken
     * @return string
     * @author:yuanHb  2020/4/29 21:18
     */
    public function getUserToken($userCenterToken, $appToken)
    {

        return config('ucenter.config.app_name').'-'.$userCenterToken['user_access_token'].'-'.$appToken;
    }

    /**
     * 用户信息更新分发
     * @param $params
     * @return boolean
     * @author:yuanHb  2020/4/28 16:05
     */
    public function userCenterStudentUpdateByNsq($params)
    {
        try {
            $nsq = $this->connect->getNsqService();
            $nsq::PubCommonMessage(config('ucenter.nsq.topic'), config('ucenter.nsq.student_update'), 'POST',
                ["params" => $params]);
        } catch (\Exception $exception) {
            return false;
        }
        return true;
    }
}
