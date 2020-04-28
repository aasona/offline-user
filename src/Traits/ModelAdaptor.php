<?php
/**
 * User: yuanHb <1214846385@qq.com>
 * Date: 2020/4/26
 * Time: 9:27.
 */


namespace Xthk\Ucenter\offline\Traits;


trait ModelAdaptor
{


    /**
     * CityModel 实列化
     * @return mixed
     * @author:yuanHb  2020/4/26 9:46
     */
    public function getCityModel()
    {
        return $this->getModelByPath($this->getModelPath('CityModel'));
    }


    /**
     * UserCenterLogModel 实列化
     * @return mixed
     * @author:yuanHb  2020/4/26 16:09
     */
    public function getLogModel()
    {

        return $this->getModelByPath($this->getModelPath('UserCenterLogModel'));
    }


    /**
     * Nsq 服务
     * @author:yuanHb  2020/4/28 16:09
     */
    public function getNsqService(){

        return $this->getModelByPath($this->getModelPath('nsq'));
    }

    /**
     * StudentModel 学生
     * @return mixed
     * @author:yuanHb  2020/4/28 9:45
     */
    public function getStudentModel(){
        return $this->getModelByPath($this->getModelPath('StudentModel'));

    }
    /**
     * 通过路径获取模型
     *
     * @param $modelPath
     * @return mixed
     */
    protected function getModelByPath($modelPath)
    {
        return new $modelPath();
    }

    /**
     * 获取模型路径
     *
     * @param $model
     * @return string
     */
    protected function getModelPath($model)
    {
        return config('ucenter.model.'.$model);
    }

}
