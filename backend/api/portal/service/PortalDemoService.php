<?php

namespace api\portal\service;

class PortalDemoService
{
    public function bootstrap()
    {
        $channels = array_values($this->channels());
        $contents = $this->sampleContents();

        return [
            'project' => [
                'name' => 'SybMenhu 同城门户',
                'subtitle' => '招聘、房产、车辆、商家、圈子一体化门户',
                'version' => '1.0.0',
            ],
            'default_city' => $this->guessCity([]),
            'banner' => [
                [
                    'title' => '本地生活门户',
                    'desc'  => '一个前后端分离的 ThinkCMF5 + uni-app Vue2 版本',
                    'image' => 'https://dummyimage.com/750x320/f26628/ffffff&text=MENHU',
                ],
                [
                    'title' => '演示账号',
                    'desc'  => '18888888881 / 18888888881',
                    'image' => 'https://dummyimage.com/750x320/10c194/ffffff&text=DEMO',
                ],
            ],
            'stats' => [
                ['label' => '今日新增', 'value' => 128],
                ['label' => '活跃商家', 'value' => 356],
                ['label' => '同城帖子', 'value' => 921],
                ['label' => '在线用户', 'value' => 1578],
            ],
            'tabs' => [
                ['name' => '首页', 'path' => '/pages/tab/home'],
                ['name' => '头条', 'path' => '/pages/tab/news'],
                ['name' => '发现', 'path' => '/pages/discovery/index'],
                ['name' => '商家', 'path' => '/pages/tab/find'],
                ['name' => '我的', 'path' => '/pages/tab/user'],
            ],
            'channel_groups' => array_slice($channels, 0, 10),
            'featured_content' => array_slice($contents, 0, 8),
            'headline_list' => $this->newsList([]),
            'merchant_list' => $this->merchantList([]),
            'discovery_list' => $this->discoveryFeed([]),
        ];
    }

    public function cities()
    {
        return [
            [
                'id'       => 100,
                'name'     => '全国',
                'children' => [
                    [
                        'id'       => 104,
                        'name'     => '上海市',
                        'children' => [
                            ['id' => 10401, 'name' => '浦东新区'],
                            ['id' => 10402, 'name' => '闵行区'],
                            ['id' => 10403, 'name' => '徐汇区'],
                        ],
                    ],
                    [
                        'id'       => 105,
                        'name'     => '杭州市',
                        'children' => [
                            ['id' => 10501, 'name' => '西湖区'],
                            ['id' => 10502, 'name' => '余杭区'],
                        ],
                    ],
                    [
                        'id'       => 106,
                        'name'     => '苏州市',
                        'children' => [
                            ['id' => 10601, 'name' => '工业园区'],
                            ['id' => 10602, 'name' => '吴中区'],
                        ],
                    ],
                ],
            ],
        ];
    }

    public function guessCity($params)
    {
        $city = isset($params['city']) ? trim($params['city']) : '';

        return [
            'id'   => 104,
            'name' => $city ?: '上海市',
            'lat'  => isset($params['lat']) ? $params['lat'] : '31.2304',
            'lng'  => isset($params['lng']) ? $params['lng'] : '121.4737',
        ];
    }

    public function uploadParams()
    {
        return [
            'policy'    => 'demo-policy',
            'signature' => 'demo-signature',
            'id'        => 'demo-access-key',
            'key'       => 'portal/demo/' . date('YmdHis') . '.jpg',
            'fullurl'   => 'https://dummyimage.com/600x400/f7f9fc/333333&text=UPLOAD',
        ];
    }

    public function channels()
    {
        return [
            'job' => [
                'key'     => 'job',
                'label'   => '招聘求职',
                'icon'    => 'briefcase',
                'summary' => '职位招聘、人才简历',
                'color'   => '#f26628',
                'fields'  => [
                    ['key' => 'title', 'label' => '职位名称', 'type' => 'text', 'required' => true],
                    ['key' => 'salary', 'label' => '薪资范围', 'type' => 'text', 'required' => true],
                    ['key' => 'company', 'label' => '公司名称', 'type' => 'text', 'required' => true],
                    ['key' => 'address', 'label' => '工作地点', 'type' => 'text', 'required' => true],
                    ['key' => 'content', 'label' => '职位描述', 'type' => 'textarea', 'required' => true],
                ],
            ],
            'resume' => [
                'key'     => 'resume',
                'label'   => '找人才',
                'icon'    => 'profile',
                'summary' => '简历展示、求职意向',
                'color'   => '#10c194',
                'fields'  => [
                    ['key' => 'title', 'label' => '求职意向', 'type' => 'text', 'required' => true],
                    ['key' => 'salary', 'label' => '期望薪资', 'type' => 'text', 'required' => true],
                    ['key' => 'education', 'label' => '学历', 'type' => 'text', 'required' => true],
                    ['key' => 'content', 'label' => '个人优势', 'type' => 'textarea', 'required' => true],
                ],
            ],
            'house_rent' => [
                'key'     => 'house_rent',
                'label'   => '房屋出租',
                'icon'    => 'home',
                'summary' => '整租、合租、短租',
                'color'   => '#4f7cff',
                'fields'  => [
                    ['key' => 'title', 'label' => '房源标题', 'type' => 'text', 'required' => true],
                    ['key' => 'price', 'label' => '租金', 'type' => 'number', 'required' => true],
                    ['key' => 'address', 'label' => '地址', 'type' => 'text', 'required' => true],
                    ['key' => 'area', 'label' => '面积', 'type' => 'text', 'required' => true],
                    ['key' => 'content', 'label' => '房源描述', 'type' => 'textarea', 'required' => true],
                ],
            ],
            'house_sell' => [
                'key'     => 'house_sell',
                'label'   => '房屋出售',
                'icon'    => 'shop',
                'summary' => '新房、二手房',
                'color'   => '#8c6cff',
                'fields'  => [
                    ['key' => 'title', 'label' => '房源标题', 'type' => 'text', 'required' => true],
                    ['key' => 'price', 'label' => '总价', 'type' => 'number', 'required' => true],
                    ['key' => 'address', 'label' => '地址', 'type' => 'text', 'required' => true],
                    ['key' => 'area', 'label' => '建筑面积', 'type' => 'text', 'required' => true],
                    ['key' => 'content', 'label' => '房源描述', 'type' => 'textarea', 'required' => true],
                ],
            ],
            'car' => [
                'key'     => 'car',
                'label'   => '二手车',
                'icon'    => 'car',
                'summary' => '车辆出售、求购',
                'color'   => '#22b8cf',
                'fields'  => [
                    ['key' => 'title', 'label' => '车辆标题', 'type' => 'text', 'required' => true],
                    ['key' => 'price', 'label' => '售价', 'type' => 'number', 'required' => true],
                    ['key' => 'brand', 'label' => '品牌车型', 'type' => 'text', 'required' => true],
                    ['key' => 'mileage', 'label' => '里程', 'type' => 'text', 'required' => true],
                    ['key' => 'content', 'label' => '车辆描述', 'type' => 'textarea', 'required' => true],
                ],
            ],
            'commodity' => [
                'key'     => 'commodity',
                'label'   => '物品交易',
                'icon'    => 'cart',
                'summary' => '闲置买卖、求购',
                'color'   => '#ff922b',
                'fields'  => [
                    ['key' => 'title', 'label' => '物品标题', 'type' => 'text', 'required' => true],
                    ['key' => 'price', 'label' => '价格', 'type' => 'number', 'required' => true],
                    ['key' => 'condition', 'label' => '成色', 'type' => 'text', 'required' => true],
                    ['key' => 'content', 'label' => '物品描述', 'type' => 'textarea', 'required' => true],
                ],
            ],
            'farming' => [
                'key'     => 'farming',
                'label'   => '农林牧渔',
                'icon'    => 'leaf',
                'summary' => '农资、农机、农产品',
                'color'   => '#2f9e44',
                'fields'  => [
                    ['key' => 'title', 'label' => '信息标题', 'type' => 'text', 'required' => true],
                    ['key' => 'price', 'label' => '价格', 'type' => 'number', 'required' => false],
                    ['key' => 'content', 'label' => '详细说明', 'type' => 'textarea', 'required' => true],
                ],
            ],
            'transfer' => [
                'key'     => 'transfer',
                'label'   => '生意转让',
                'icon'    => 'reload',
                'summary' => '店铺转让、项目转让',
                'color'   => '#fa5252',
                'fields'  => [
                    ['key' => 'title', 'label' => '项目名称', 'type' => 'text', 'required' => true],
                    ['key' => 'price', 'label' => '转让费', 'type' => 'number', 'required' => true],
                    ['key' => 'address', 'label' => '经营地址', 'type' => 'text', 'required' => true],
                    ['key' => 'content', 'label' => '经营情况', 'type' => 'textarea', 'required' => true],
                ],
            ],
            'ask' => [
                'key'     => 'ask',
                'label'   => '需求打听',
                'icon'    => 'chat',
                'summary' => '求购、打听、寻求帮助',
                'color'   => '#845ef7',
                'fields'  => [
                    ['key' => 'title', 'label' => '需求标题', 'type' => 'text', 'required' => true],
                    ['key' => 'budget', 'label' => '预算', 'type' => 'text', 'required' => false],
                    ['key' => 'content', 'label' => '详细需求', 'type' => 'textarea', 'required' => true],
                ],
            ],
            'carpool' => [
                'key'     => 'carpool',
                'label'   => '拼车出行',
                'icon'    => 'map',
                'summary' => '车找人、人找车、顺风车',
                'color'   => '#1098ad',
                'fields'  => [
                    ['key' => 'title', 'label' => '路线标题', 'type' => 'text', 'required' => true],
                    ['key' => 'route', 'label' => '路线', 'type' => 'text', 'required' => true],
                    ['key' => 'time', 'label' => '出发时间', 'type' => 'text', 'required' => true],
                    ['key' => 'content', 'label' => '备注', 'type' => 'textarea', 'required' => true],
                ],
            ],
            'promote' => [
                'key'     => 'promote',
                'label'   => '本地推广',
                'icon'    => 'megaphone',
                'summary' => '活动推广、优惠信息',
                'color'   => '#e64980',
                'fields'  => [
                    ['key' => 'title', 'label' => '推广标题', 'type' => 'text', 'required' => true],
                    ['key' => 'price', 'label' => '预算', 'type' => 'number', 'required' => false],
                    ['key' => 'content', 'label' => '活动说明', 'type' => 'textarea', 'required' => true],
                ],
            ],
        ];
    }

    public function contents($params)
    {
        $channel = isset($params['channel']) ? $params['channel'] : '';
        $keyword = isset($params['keyword']) ? trim($params['keyword']) : '';
        $city = isset($params['city']) ? trim($params['city']) : '';
        $items = $this->sampleContents();

        $items = array_values(array_filter($items, function ($item) use ($channel, $keyword, $city) {
            if ($channel && $item['channel'] !== $channel) {
                return false;
            }

            if ($city && $item['city'] !== $city) {
                return false;
            }

            if ($keyword && !$this->contains($item['title'] . ' ' . $item['summary'], $keyword)) {
                return false;
            }

            return true;
        }));

        return [
            'channel' => $channel,
            'keyword' => $keyword,
            'city'    => $city,
            'list'    => $items,
            'total'   => count($items),
        ];
    }

    public function contentDetail($id)
    {
        $items = $this->sampleContents();
        foreach ($items as $item) {
            if ($item['id'] === $id) {
                $item['detail'] = [
                    'content' => $item['summary'] . '，支持图文详情、联系方式、收藏与分享。',
                    'gallery' => [
                        'https://dummyimage.com/900x600/f7f9fc/333333&text=' . urlencode($item['title']),
                        'https://dummyimage.com/900x600/fff3bf/333333&text=' . urlencode($item['channel_name']),
                    ],
                    'contact' => [
                        'name'   => $item['author'],
                        'mobile' => '18888888881',
                        'wechat' => 'menhu-demo',
                    ],
                    'recommend' => array_slice($items, 0, 3),
                ];
                return $item;
            }
        }

        return null;
    }

    public function publishContent($params)
    {
        $channel = isset($params['channel']) ? $params['channel'] : 'job';
        $channels = $this->channels();
        $channelConfig = isset($channels[$channel]) ? $channels[$channel] : $channels['job'];

        return [
            'id'           => 'draft-' . date('YmdHis'),
            'channel'      => $channelConfig['key'],
            'channel_name' => $channelConfig['label'],
            'title'        => isset($params['title']) ? $params['title'] : $channelConfig['label'] . '示例信息',
            'status'       => 'published',
            'audit_status' => 'approved',
            'created_at'   => date('Y-m-d H:i:s'),
        ];
    }

    public function newsList($params)
    {
        $keyword = isset($params['keyword']) ? trim($params['keyword']) : '';
        $list = $this->sampleNews();

        if ($keyword) {
            $list = array_values(array_filter($list, function ($item) use ($keyword) {
                return $this->contains($item['title'] . ' ' . $item['summary'], $keyword);
            }));
        }

        return $list;
    }

    public function newsDetail($id)
    {
        foreach ($this->sampleNews() as $item) {
            if ($item['id'] === $id) {
                $item['content'] = implode("\n\n", [
                    '这是一个演示版的头条详情页，适合替换成门户公告、活动资讯、同城动态。',
                    '后端可接入 ThinkCMF 文章模型，前端则复用当前详情页模板。',
                    '你可以把广告位、专题页、分享海报、阅读量统计继续挂在这里。',
                ]);
                return $item;
            }
        }

        return null;
    }

    public function merchantList($params)
    {
        $keyword = isset($params['keyword']) ? trim($params['keyword']) : '';
        $category = isset($params['category']) ? trim($params['category']) : '';
        $list = $this->sampleMerchants();

        $list = array_values(array_filter($list, function ($item) use ($keyword, $category) {
            if ($category && $item['category'] !== $category) {
                return false;
            }

            if ($keyword && !$this->contains($item['name'] . ' ' . $item['summary'], $keyword)) {
                return false;
            }

            return true;
        }));

        return $list;
    }

    public function merchantDetail($id)
    {
        foreach ($this->sampleMerchants() as $item) {
            if ($item['id'] === $id) {
                $item['services'] = ['团购', '优惠券', '到店核销', '在线咨询'];
                $item['gallery'] = [
                    'https://dummyimage.com/900x600/e9ecef/333333&text=' . urlencode($item['name']),
                    'https://dummyimage.com/900x600/f8f0fc/333333&text=' . urlencode($item['category_name']),
                ];
                $item['comments'] = [
                    ['user' => '张先生', 'score' => 5, 'content' => '店铺环境不错，服务态度好。'],
                    ['user' => '王女士', 'score' => 4, 'content' => '活动价格合适，值得推荐。'],
                ];
                return $item;
            }
        }

        return null;
    }

    public function commentMerchant($params)
    {
        return [
            'merchant_id' => isset($params['merchant_id']) ? $params['merchant_id'] : '',
            'score'       => isset($params['score']) ? (int) $params['score'] : 5,
            'content'     => isset($params['content']) ? $params['content'] : '好评',
            'created_at'  => date('Y-m-d H:i:s'),
        ];
    }

    public function applyMerchant($params)
    {
        return [
            'application_no' => 'MER' . date('YmdHis'),
            'store_name'     => isset($params['store_name']) ? $params['store_name'] : '示例店铺',
            'status'         => 'pending',
        ];
    }

    public function discoveryFeed($params)
    {
        $keyword = isset($params['keyword']) ? trim($params['keyword']) : '';
        $list = $this->sampleDiscovery();

        if ($keyword) {
            $list = array_values(array_filter($list, function ($item) use ($keyword) {
                return $this->contains($item['title'] . ' ' . $item['content'], $keyword);
            }));
        }

        return $list;
    }

    public function discoveryDetail($id)
    {
        foreach ($this->sampleDiscovery() as $item) {
            if ($item['id'] === $id) {
                $item['comments'] = [
                    ['user' => '圈友A', 'content' => '这个活动我想参加。'],
                    ['user' => '圈友B', 'content' => '地点方便，周末约起来。'],
                ];
                return $item;
            }
        }

        return null;
    }

    public function publishDiscovery($params)
    {
        return [
            'id'         => 'topic-' . date('YmdHis'),
            'title'      => isset($params['title']) ? $params['title'] : '新的同城帖子',
            'content'    => isset($params['content']) ? $params['content'] : '',
            'created_at' => date('Y-m-d H:i:s'),
        ];
    }

    public function dashboard()
    {
        return [
            'user' => $this->demoUser(),
            'stats' => [
                ['label' => '我的发布', 'value' => 12],
                ['label' => '我的收藏', 'value' => 8],
                ['label' => '收到消息', 'value' => 16],
                ['label' => '会员等级', 'value' => 'VIP1'],
            ],
            'menus' => [
                ['name' => '发布管理', 'path' => '/pages/tab/publish'],
                ['name' => '我的收藏', 'path' => '/pages/tab/user'],
                ['name' => '帮助中心', 'path' => '/pages/tab/user'],
                ['name' => '意见反馈', 'path' => '/pages/tab/user'],
            ],
            'latest_publish' => array_slice($this->sampleContents(), 0, 4),
        ];
    }

    public function collections()
    {
        return array_slice($this->sampleContents(), 0, 3);
    }

    public function toggleCollection($params)
    {
        return [
            'content_id' => isset($params['content_id']) ? $params['content_id'] : '',
            'collected'  => isset($params['collected']) ? (bool) $params['collected'] : true,
        ];
    }

    public function feedback($params)
    {
        return [
            'ticket_no' => 'FDB' . date('YmdHis'),
            'content'   => isset($params['content']) ? $params['content'] : '',
        ];
    }

    public function authStatus()
    {
        return [
            'check_status' => 1,
            'check_reason' => '',
            'real_name'    => '演示用户',
        ];
    }

    public function login($params)
    {
        $mobile = isset($params['mobile']) ? trim($params['mobile']) : '';
        $password = isset($params['password']) ? trim($params['password']) : '';

        if ($mobile === '18888888881' && $password === '18888888881') {
            return [
                'ok'      => true,
                'message' => '登录成功',
                'data'    => [
                    'token'    => 'demo-token',
                    'userinfo' => $this->demoUser(),
                ],
            ];
        }

        if ($mobile && $password) {
            return [
                'ok'      => true,
                'message' => '登录成功',
                'data'    => [
                    'token'    => 'token-' . md5($mobile),
                    'userinfo' => array_merge($this->demoUser(), ['mobile' => $mobile]),
                ],
            ];
        }

        return [
            'ok'      => false,
            'message' => '手机号或密码不能为空',
            'data'    => [],
        ];
    }

    public function register($params)
    {
        return [
            'token'    => 'register-token',
            'userinfo' => [
                'id'       => 2,
                'nickname' => isset($params['nickname']) ? $params['nickname'] : '新用户',
                'mobile'   => isset($params['mobile']) ? $params['mobile'] : '18800000000',
                'avatar'   => 'https://dummyimage.com/120x120/10c194/ffffff&text=U',
            ],
        ];
    }

    public function wxLogin($params)
    {
        return [
            'third' => [
                'binding'   => 1,
                'platform'  => isset($params['platform']) ? $params['platform'] : 'wxmini',
                'open_id'   => 'demo-open-id',
            ],
            'userinfo' => $this->demoUser(),
        ];
    }

    protected function demoUser()
    {
        return [
            'id'       => 1,
            'nickname' => '演示账号',
            'mobile'   => '18888888881',
            'avatar'   => 'https://dummyimage.com/120x120/f26628/ffffff&text=VIP',
            'city'     => '上海市',
            'vip_name' => '年度会员',
        ];
    }

    protected function sampleContents()
    {
        return [
            [
                'id'           => 'c-job-1',
                'channel'      => 'job',
                'channel_name' => '招聘求职',
                'title'        => '新媒体运营专员',
                'summary'      => '负责本地生活内容运营、活动策划，月薪 7K-10K。',
                'price'        => '7K-10K',
                'city'         => '上海市',
                'address'      => '徐汇区漕河泾',
                'author'       => '星河传媒',
                'tag'          => '急聘',
                'created_at'   => '2026-03-09 09:00:00',
            ],
            [
                'id'           => 'c-resume-1',
                'channel'      => 'resume',
                'channel_name' => '找人才',
                'title'        => '3 年前端工程师求职',
                'summary'      => '熟悉 uni-app、Vue2、微信小程序，希望寻找上海本地岗位。',
                'price'        => '期望 12K',
                'city'         => '上海市',
                'address'      => '浦东新区',
                'author'       => '李先生',
                'tag'          => '可立即到岗',
                'created_at'   => '2026-03-09 08:30:00',
            ],
            [
                'id'           => 'c-house-rent-1',
                'channel'      => 'house_rent',
                'channel_name' => '房屋出租',
                'title'        => '地铁口精装两居室整租',
                'summary'      => '近 2 号线，家电齐全，拎包入住。',
                'price'        => '5200 元/月',
                'city'         => '上海市',
                'address'      => '浦东新区张江',
                'author'       => '张女士',
                'tag'          => '精装',
                'created_at'   => '2026-03-09 07:50:00',
            ],
            [
                'id'           => 'c-house-sell-1',
                'channel'      => 'house_sell',
                'channel_name' => '房屋出售',
                'title'        => '学区房三室两厅出售',
                'summary'      => '总价 398 万，满五唯一，交通便利。',
                'price'        => '398 万',
                'city'         => '上海市',
                'address'      => '闵行区七宝',
                'author'       => '周先生',
                'tag'          => '学区',
                'created_at'   => '2026-03-08 18:20:00',
            ],
            [
                'id'           => 'c-car-1',
                'channel'      => 'car',
                'channel_name' => '二手车',
                'title'        => '2021 款比亚迪秦 PLUS',
                'summary'      => '个人一手车，5.2 万公里，支持检测。',
                'price'        => '8.68 万',
                'city'         => '上海市',
                'address'      => '闵行区',
                'author'       => '陈先生',
                'tag'          => '个人车',
                'created_at'   => '2026-03-08 17:10:00',
            ],
            [
                'id'           => 'c-commodity-1',
                'channel'      => 'commodity',
                'channel_name' => '物品交易',
                'title'        => '九成新投影仪转让',
                'summary'      => '家用高清投影，支持试机，配件齐全。',
                'price'        => '1200 元',
                'city'         => '上海市',
                'address'      => '徐汇区',
                'author'       => '王女士',
                'tag'          => '自提优先',
                'created_at'   => '2026-03-08 16:30:00',
            ],
            [
                'id'           => 'c-farming-1',
                'channel'      => 'farming',
                'channel_name' => '农林牧渔',
                'title'        => '温室草莓采摘基地合作',
                'summary'      => '提供周末采摘、团建活动，欢迎商家合作。',
                'price'        => '面议',
                'city'         => '苏州市',
                'address'      => '吴中区',
                'author'       => '田园农场',
                'tag'          => '合作',
                'created_at'   => '2026-03-08 15:40:00',
            ],
            [
                'id'           => 'c-transfer-1',
                'channel'      => 'transfer',
                'channel_name' => '生意转让',
                'title'        => '社区餐饮店低价转让',
                'summary'      => '临街商铺，设备齐全，接手即可营业。',
                'price'        => '6.8 万',
                'city'         => '杭州市',
                'address'      => '西湖区',
                'author'       => '林先生',
                'tag'          => '临街',
                'created_at'   => '2026-03-08 14:50:00',
            ],
            [
                'id'           => 'c-ask-1',
                'channel'      => 'ask',
                'channel_name' => '需求打听',
                'title'        => '求租可带宠物的一居室',
                'summary'      => '预算 4500 以内，希望近地铁，接受老小区。',
                'price'        => '预算 4500',
                'city'         => '上海市',
                'address'      => '杨浦区',
                'author'       => '赵女士',
                'tag'          => '求租',
                'created_at'   => '2026-03-08 11:00:00',
            ],
            [
                'id'           => 'c-carpool-1',
                'channel'      => 'carpool',
                'channel_name' => '拼车出行',
                'title'        => '周五上海到苏州车找人',
                'summary'      => '18:30 出发，尚余 3 座，可到园区。',
                'price'        => '60 元/人',
                'city'         => '上海市',
                'address'      => '虹桥火车站',
                'author'       => '高先生',
                'tag'          => '车找人',
                'created_at'   => '2026-03-08 10:20:00',
            ],
            [
                'id'           => 'c-promote-1',
                'channel'      => 'promote',
                'channel_name' => '本地推广',
                'title'        => '周末亲子烘焙体验营',
                'summary'      => '9.9 元抢体验券，适合 4-10 岁儿童家庭。',
                'price'        => '9.9 元',
                'city'         => '上海市',
                'address'      => '静安区',
                'author'       => '甜点工作室',
                'tag'          => '限时活动',
                'created_at'   => '2026-03-08 09:40:00',
            ],
        ];
    }

    protected function sampleNews()
    {
        return [
            [
                'id'         => 'n-1',
                'title'      => '同城门户新版正式上线',
                'summary'    => '首页、头条、商家、发布、发现五大模块统一升级。',
                'cover'      => 'https://dummyimage.com/750x360/f26628/ffffff&text=NEWS+1',
                'created_at' => '2026-03-09 08:00:00',
            ],
            [
                'id'         => 'n-2',
                'title'      => '本周商家入驻优惠活动开启',
                'summary'    => '餐饮、美业、教育行业均可申请优惠入驻。',
                'cover'      => 'https://dummyimage.com/750x360/10c194/ffffff&text=NEWS+2',
                'created_at' => '2026-03-08 12:00:00',
            ],
            [
                'id'         => 'n-3',
                'title'      => '春季招聘专场上线',
                'summary'    => '新增职位筛选、简历直投、聊天咨询等功能。',
                'cover'      => 'https://dummyimage.com/750x360/4f7cff/ffffff&text=NEWS+3',
                'created_at' => '2026-03-07 16:00:00',
            ],
        ];
    }

    protected function sampleMerchants()
    {
        return [
            [
                'id'            => 'm-1',
                'name'          => '匠心美发沙龙',
                'category'      => 'beauty',
                'category_name' => '丽人美容',
                'summary'       => '洗剪吹、烫染、护理一站式服务。',
                'score'         => 4.8,
                'address'       => '上海市徐汇区漕溪北路 188 号',
                'phone'         => '18888880001',
                'cover'         => 'https://dummyimage.com/750x360/f8f0fc/333333&text=STORE+1',
            ],
            [
                'id'            => 'm-2',
                'name'          => '江南小馆',
                'category'      => 'food',
                'category_name' => '美食餐饮',
                'summary'       => '本帮菜、家庭聚餐、团购套餐。',
                'score'         => 4.7,
                'address'       => '上海市浦东新区张江路 99 号',
                'phone'         => '18888880002',
                'cover'         => 'https://dummyimage.com/750x360/fff3bf/333333&text=STORE+2',
            ],
            [
                'id'            => 'm-3',
                'name'          => '星火托管中心',
                'category'      => 'education',
                'category_name' => '教育培训',
                'summary'       => '课后托管、作业辅导、兴趣课程。',
                'score'         => 4.9,
                'address'       => '上海市闵行区古美路 66 号',
                'phone'         => '18888880003',
                'cover'         => 'https://dummyimage.com/750x360/e7f5ff/333333&text=STORE+3',
            ],
        ];
    }

    protected function sampleDiscovery()
    {
        return [
            [
                'id'         => 'd-1',
                'title'      => '周末徒步活动招募',
                'content'    => '计划去佘山徒步，欢迎同城朋友一起参加。',
                'topic'      => '同城活动',
                'author'     => '阿木',
                'likes'      => 32,
                'comments'   => 8,
                'created_at' => '2026-03-09 07:30:00',
            ],
            [
                'id'         => 'd-2',
                'title'      => '想找靠谱搬家推荐',
                'content'    => '月底搬家，有没有用过不错的搬家公司？',
                'topic'      => '生活互助',
                'author'     => '糖糖',
                'likes'      => 16,
                'comments'   => 11,
                'created_at' => '2026-03-08 20:10:00',
            ],
            [
                'id'         => 'd-3',
                'title'      => '浦东宠物友好咖啡馆合集',
                'content'    => '整理了 5 家我去过的宠物友好店铺，欢迎补充。',
                'topic'      => '吃喝玩乐',
                'author'     => 'Momo',
                'likes'      => 54,
                'comments'   => 13,
                'created_at' => '2026-03-08 13:00:00',
            ],
        ];
    }

    protected function contains($text, $keyword)
    {
        return mb_stripos($text, $keyword) !== false;
    }
}
