<?php
/**
 * User: yuanHb <1214846385@qq.com>
 * Date: 2020/4/24
 * Time: 13:45.
 */

namespace Xthk\Ucenter\offline\Support;

class Params
{

    /**
     * @var array
     */
    protected $params;

    /**
     * params constructor.
     *
     * @param  array  $params
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  string  $key
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $params = $this->params;

        if (isset($params[$key])) {
            return $params[$key];
        }

        if (false === strpos($key, '.')) {
            return $default;
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($params) || !array_key_exists($segment, $params)) {
                return $default;
            }
            $params = $params[$segment];
        }

        return $params;
    }


    public function getParamsForConfig()
    {
        return config('ucenter.params');
    }


    public function getParams(){

        return $this->params;
    }

}
