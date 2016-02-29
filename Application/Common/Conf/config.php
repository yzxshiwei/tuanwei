<?php
return array(
	//'配置项'=>'配置值'
    'LOAD_EXT_CONFIG'=>"db",
    "URL_MODEL"=>0,
    "TMPL_L_DELIM"=>"{{",
    "TMPL_R_DELIM"=>"}}",
    "TMPL_PARSE_STRING"=>array(
        "__STATIC__"=>WEB_ROOT."static",
        "__JS__"=>WEB_ROOT."static/javascript/",
        "__STATICBK__"=>WEB_ROOT."static/backend"
    ),
    "SESSION_AUTO_START"=>true,
    //权限
    "PERMISSION"=>array(
        "usermanage" => array('name'=>"人员管理",
                            'url'=>U('Index/usermanage')),
        "teammanage" => array('name' => "团队管理",
                             'url'=>U('Index/teammanage')),
        "createnews" => array('name' => "创建信息",
                              'url'=>U('News/createnews')),
        "creatematch" => array('name' => "创建比赛",
                               'url' => U('Match/creatematch')),
        "createproject" => array('name' => "创建项目",
                                 'url' => U('Project/createproject')),
        "matchmanage" => array('name' => "比赛管理",
                               'url' => U('Match/matchmanage')),
        "newsmanage" => array('name' => "信息管理",
                              'url' => U('News/newsmanage')),
        "teamdiscuss" => array('name' => "团队交流",
                               'url' => U('Team/teamdiscuss')),
        "projectmanage" => array('name' => "项目管理",
                                 'url' => U('Project/projectmanage')),
        "grouplist" => array('name' => "分组列表",
                             'url' => U('Permission/grouplist')),
        "addgroup" => array('name' => "添加分组",
                            'url' => U('Permission/addgroup'))
    ),
);