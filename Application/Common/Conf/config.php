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
        "message"    => array("name"=>"消息中心",
                              "url"=>"Index/index",
		                      "ico"=>"fa fa-lightbulb-o",
		                      "map"=>array("index")
							 ),
        "usermanage" => array('name'=>"人员管理",
                              'url'=>'Index/usermanage',
                              'ico' => 'fa fa-user',
							  'map' =>array("usermanage","person","agree","refuse","read","addteam")
							  ),
        
        "teammanage" => array('name' => "团队管理",
                             'url'=>'Index/teammanage',
                             'ico' => 'fa fa-users',
							 'map' =>array("teammanage","updateteam")
							 ),
        
        "createnews" => array('name' => "创建信息",
                              'url'=>'News/createnews',
                              'ico' => 'fa fa-lightbulb-o',
							  'map' =>array("createnews")
							  ),
        
        "creatematch" => array('name' => "创建比赛",
                               'url' => 'Match/creatematch',
                               'ico' => 'fa  fa-graduation-cap',
							   'map' => array("creatematch")
							   ),
        
        "createproject" => array('name' => "创建项目",
                                 'url' => 'Project/createproject',
                                 'ico' => 'fa fa-bookmark-o',
								 'map' => array("createproject")
								 ),
        
        "matchmanage" => array('name' => "比赛管理",
                               'url' => 'Match/matchmanage',
                               'ico' => 'fa fa-futbol-o',
                               'map' => array("matchmanage","editmatch","viewmatch")
							   ),
        
        "newsmanage" => array('name' => "信息管理",
                              'url' => 'News/newsmanage',
                              'ico' => 'fa fa-cubes',
							  'map' =>array("newsmanage","newedit")
							  ),
        
//      "teamdiscuss" => array('name' => "团队交流",
//                             'url' => 'Team/teamdiscuss',
//                             'ico' => 'fa fa-language',
//							   'map' =>array("teamdiscuss")
//							   ),
        
        "projectmanage" => array('name' => "项目管理",
                                 'url' => 'Project/projectmanage',
                                 'ico' => 'fa fa-share-alt',
								 'map' => array("projectmanage","workreview","editproject")
								 ),
        
        "grouplist" => array('name' => "分组列表",
                             'url' => 'Permission/grouplist',
                             'ico' => 'fa fa-bars',
							 'map' =>array("grouplist","addpermission","usergroup")
							 )
        
//      "addgroup" => array('name' => "创建分组",
//                          'url' => 'Permission/addgroup',
//                          'ico' => 'fa fa-suitcase',
//							'map' =>array("addgroup")
//							)
    ),
);