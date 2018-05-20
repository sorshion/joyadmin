<?php defined('BASEPATH') OR exit('No direct script access allowed');


// 不需要权限的url
$config['rule_uri'] = [
    // 整个控制器过滤
    'float' => [
    ],
    // 过滤特定action
    'nofloat' => [
    ]
];
