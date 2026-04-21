<?php

if (!function_exists('tp_admin_url')) {
    function tp_admin_url($path, array $params = [])
    {
        $query = '/index.php?s=/' . ltrim($path, '/');
        if ($params) {
            $query .= '/' . http_build_query($params, '', '/');
        }
        return $query;
    }
}
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
