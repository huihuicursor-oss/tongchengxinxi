<?php

namespace app\admin\controller;

class Elder extends BaseController
{
    public function index()
    {
        return $this->renderPage('elder', [
            'pageTitle' => '老人档案',
            'pageDescription' => '入住信息、护理等级、房间分布与家属联系方式。',
            'profiles' => $this->repo->elderProfiles(),
        ]);
    }
}
