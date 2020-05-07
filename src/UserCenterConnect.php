<?php
/**
 * User: yuanHb <1214846385@qq.com>
 * Date: 2020/4/24
 * Time: 15:07.
 */


namespace Xthk\Ucenter\offline;


use Xthk\Ucenter\offline\Exceptions\CustomException;
use Xthk\Ucenter\offline\Exceptions\Exception;
use Xthk\Ucenter\offline\Exceptions\InvalidArgumentException;
use Xthk\Ucenter\offline\Support\Constants;
use Xthk\Ucenter\offline\Support\Params;
use Xthk\Ucenter\offline\Traits\ModelAdaptor;
use Xthk\Ucenter\User\OperateUcenter;

class UserCenterConnect extends OperateUcenter
{
    use ModelAdaptor;

    /**
     * 输入参数
     * @var array
     */
    protected $input;

    /**
     * 限制参数
     * @var array
     */
    protected $configParams;

    /**
     * 日志模型
     * @var UserCenterLogModel
     */
    protected $log;

    protected $ip;


    /**
     * 设置参数
     * @param  array  $params
     * @throws InvalidArgumentException
     * @author:yuanHb  2020/4/24 15:57
     */
    public function setInput(array $params)
    {

        $this->input = new Params($params);
        //参数判断
        $configParams = $this->input->getParamsForConfig();
        //参数获取
        foreach ($configParams as $key => $configParam) {
            $this->params[$key] = $this->input->get($key) ? $this->input->get($key) : $this->input->get($configParam);
        }
        $this->params = array_filter($this->params);
        //城市id转换
        if(isset($this->params['city_id'])){
            $this->cityMap();
        }
    }

    /**
     * user_access_token 参数设置
     * @param $userAccessToken
     * @author:yuanHb  2020/4/28 10:35
     */
    public function setUserAccessToken($userAccessToken)
    {
        $this->params['user_access_token'] = $userAccessToken;
    }

    /**
     * 城市id映射
     * @author:yuanHb  2020/4/26 11:51
     */
    protected function cityMap()
    {
        if (isset($this->params['city_id'])) {
            $city = $this->getCityModel()->find($this->params['city_id']);
            if (!$city) {
                throw new InvalidArgumentException('Invalid type value(city_id): '.$this->params['city_id']);
            }
            $this->params['city_id'] = $city->base_districts_id;
            $this->params['province_id'] = $city->base_districts_province_id;
        }
    }

    /**
     * 创建日志
     * @param $url
     * @throws Exception
     * @author:yuanHb  2020/4/26 15:48
     */
    public function createLog($url)
    {
        try {
            $this->log = $this->getLogModel();
            //日志写入
            $logData = [
                'request_url' => $url,
                'student_phone' => $this->input->get('mobile'),
                'status' => Constants::LOG_STATUS_UNTREATED
            ];
            $this->log->create($logData);
        } catch (\Exception $exception) {
            throw new Exception('write log failed');
        }
    }


    /**
     * 日志更新
     * @param $status
     * @throws Exception
     * @author:yuanHb  2020/4/26 17:05
     */
    public function updateLog($status)
    {
        try {
            $this->log->status = $status;
            $this->log->save();
        } catch (\Exception $exception) {
            throw new Exception('update log failed');
        }
    }

    /**
     * 通过学生电话返回用户id
     * @author:yuanHb  2020/4/29 21:43
     */
    public function getUserIdByStudentPhone(){
        $student = $this->getStudentModel()->where('phone', $this->params['mobile'])->first();
        if($student){
            return $student->user_id;
        } else {
            return null;
        }
    }

    /**
     * 返回组装
     * @param $result
     * @param  string  $type
     * @return array|bool
     * @throws CustomException
     * @author:yuanHb  2020/4/29 22:23
     */
    public function response($result, $type = 'array')
    {
        \Log::info($result);
        $result = json_decode($result, true);
        if (!isset($result['status_code'])) {
            return false;
        }
        if ($result['status_code'] === 400) {
            throw new CustomException($result['message']);
        }
        if ($result['status_code'] === 200) {
            switch ($type) {
                case 'bool':
                    return true;
                case 'array':
                    return (array) $result['data'];
            }
        }
        return false;
    }

    /**
     * 设置ip
     * @author:yuanHb  2020/4/30 10:28
     */
    public function setClientIp(){
        if(!$this->ip == null){
            return;
        }
        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $this->ip = getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $this->ip = getenv('HTTP_X_FORWARDED_FOR');
//        } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
//            $ip = getenv('REMOTE_ADDR');
        } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $this->ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $this->ip = '0.0.0.0';
        }
        $this->ip = preg_match('/[\d\.]{7,15}/', $this->ip, $matches) ? $matches[0] : '';
    }

    /**
     * 返回ip
     * @return mixed
     * @author:yuanHb  2020/4/30 10:29
     */
    public function getClientIp(){
        return $this->ip;
    }


}
