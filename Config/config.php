<?php

return [
    'name' => 'Smallticket',

    // 插件标识，没事不要取修改，后果自负
    'module' => 'small-ticket',


    /**
     * 打印机品牌  没事不要取修改，后果自负
     * @parent name 打印机品牌
     * @parent type 打印机类型
     * @parent url 打印机官网
     */
    'brand' => [
        [
            "name" => "365云打印",
            "url" => "http://www.printcenter.cn/",
            "param" => [
                "device_no" => [
                    "la" => "打印机编号",
                    "va" => "",
                ],
                "key" => [
                    "la" => "打印机密钥",
                    "va" => "",
                ],
                "times" => [
                    "la" => "打印机联数",
                    "va" => "",
                ],
            ],
        ],
        [
            "name" => "易联云",
            "type" => [
                "k4"
            ],
            "url" => "https://www.yilianyun.net/",
            "param" => [
                "client_id" => [
                    "la" => "开发者应用ID",
                    "va" => "",
                ],
                "api_key" => [
                    "la" => "apiKey",
                    "va" => "",
                ],
                "machine_code" => [
                    "la" => "打印机终端号",
                    "va" => "",
                ],
                "machine_key" => [
                    "la" => "打印机终端密钥",
                    "va" => "",
                ],
                "times" => [
                    "la" => "打印机联数",
                    "va" => "",
                ],
            ],
        ],
        [
            "name" => "飞鹅",
            "type" => [
                "小票机",
                "标签机",
            ],
            "url" => "http://www.feieyun.com/",
            "param" => [
                "user" => [
                    "la" => "飞鹅后台登录名",
                    "va" => "",
                ],
                "ukey" => [
                    "la" => "UKEY",
                    "va" => "",
                ],
                "sn" => [
                    "la" => "打印机编号",
                    "va" => "",
                ],
                "key" => [
                    "la" => "打印机key",
                    "va" => "",
                ],
                "times" => [
                    "la" => "打印机联数",
                    "va" => "",
                ],
            ],
        ]
    ],

];
