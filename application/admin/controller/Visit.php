<?php

namespace app\admin\controller;

class Visit extends BaseController
{
    public function index()
    {
        return $this->renderPage('visit', [
            'pageTitle' => '家属探访',
            'pageDescription' => '家属预约、审核、到访登记一体化管理。',
            'visitSummary' => $this->repo->visitSummary(),
            'visits' => $this->repo->visits(),
        ]);
    }
}
