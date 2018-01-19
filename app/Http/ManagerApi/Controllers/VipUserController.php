<?php

/**
 * @author xiaojian
 * @file VipUserController.php
 * @info 会员管理控制器
 * @date 2018年01月16日17:01:30
 */

namespace App\Http\ManagerApi\Controllers;

use App\Http\ManagerApi\Controllers\Controller;
use App\Models\StoreVipUser;

class VipUserController extends Controller
{
    /**
     * @name   获取会员了列表
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息,datas:查询的数据]
     */
    public function listVipUsers()
    {
        $params = $this->api->checkParams(
            ['limit:integer', 'offset:integer'],
            ['nick:max:40', 'phone:max:40', 'vip_level:integer', 'gender:integer']
        );

        // 查询参数
        $search_params = [
            'nick' => ['where', 'like'],
            'phone' => ['where', 'like'],
            'vip_level' => ['where', '='],
            'gender' => ['where', '='],
        ];

        if (isset($params['nick'])) {
            $params['nick'] = "%{$params['nick']}%";
        }
        if (isset($params['phone'])) {
            $params['phone'] = "%{$params['phone']}%";
        }

        $datas = with(new StoreVipUser)->search($params, $search_params);
        return $this->api->paginate($datas);
    }

    /**
     * @name   获取会员详情,提供会员id
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息,datas:查询的数据]
     */
    public function getVipUser()
    {
        $params = $this->api->checkParams(['id:integer']);

        // 对于id是错误的,查询不到用户时候,有两种选择方案
        
        // 方案一,直接使用ORM中内置的异常抛出,findOrFail,会在查询不到的时候抛出异常,这个异常会被统一处理返回给前端
        $vip_user = StoreVipUser::findOrFail($params['id']);
        return $this->api->datas($vip_user);

        // 方案二,使用api特性中的data方法,如果参数是空值,会返回错误信息
        // return $this->api->data(StoreVipUser::find($params['id']), 'success', '请求的用户数据不存在');
    }
}
