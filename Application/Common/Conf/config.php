<?php
return array(
	//'配置项'=>'配置值'
    'LOAD_EXT_CONFIG'=>"db",
    "URL_MODEL"=>1,
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
        "message"    => array("name"=>"消息中心",
                              "url"=>"Index/index",
		                      "ico"=>"message",
		                      "map"=>array("index")
							 ),
        "usermanage" => array('name'=>"人员管理",
                              'url'=>'Index/usermanage',
                              'ico' => 'person',
							  'map' =>array("usermanage","person","agree","refuse","read","addteam")
							  ),
        
        "teammanage" => array('name' => "团队管理",
                             'url'=>'Index/teammanage',
                             'ico' => 'teamimg',
							 'map' =>array("teammanage","updateteam")
							 ),
        
        "createnews" => array('name' => "创建信息",
                              'url'=>'News/createnews',
                              'ico' => 'createinformation',
							  'map' =>array("createnews")
							  ),
       "newsmanage" => array('name' => "信息管理",
                              'url' => 'News/newsmanage',
                              'ico' => 'management',
							  'map' =>array("newsmanage","newedit")
							  ),
        "creatematch" => array('name' => "创建比赛",
                               'url' => 'Match/creatematch',
                               'ico' => 'creatematch',
							   'map' => array("creatematch")
							   ),
        "matchmanage" => array('name' => "比赛管理",
                               'url' => 'Match/matchmanage',
                               'ico' => 'match',
                               'map' => array("matchmanage","editmatch","viewmatch")
							   ),
        "createproject" => array('name' => "创建项目",
                                 'url' => 'Project/createproject',
                                 'ico' => 'createproject',
								 'map' => array("createproject")
								 ),
        "projectmanage" => array('name' => "项目管理",
                                 'url' => 'Project/projectmanage',
                                 'ico' => 'projectimg',
								 'map' => array("projectmanage","workreview","editproject")
								 ),

        "teamdiscuss" => array('name' => "资金申请",
                            'url' => 'Funds/fundsmanage',
                            'ico' => 'createinformation',
                 'map' =>array("createfunds","editfunds","fundsmanage")
                 ),
        
        "grouplist" => array('name' => "权限分配",
                             'url' => 'Permission/grouplist',
                             'ico' => 'authority',
							   'map' =>array("grouplist","addpermission","usergroup")
							   )

//      "teamdiscuss" => array('name' => "团队交流",
//                             'url' => 'Team/teamdiscuss',
//                             'ico' => 'fa fa-language',
//							   'map' =>array("teamdiscuss")
//							   ),
        
//      "addgroup" => array('name' => "创建分组",
//                          'url' => 'Permission/addgroup',
//                          'ico' => 'fa fa-suitcase',
//							'map' =>array("addgroup")
//							)
    ),
);