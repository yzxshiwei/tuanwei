

var root_list = {
    "menu_list": [
	    {
	        "url": "user_manage.html",
	        "name": "人员管理",
	        "class": "fa-user"
	    }, {
	        "url": "team_manage.html",
	        "name": "团队管理",
	        "class": "fa-users"
	    }
	    , {
	        "url": "new_match.html",
	        "name": "创建比赛",
	        "class": "fa-lightbulb-o"
	    }
	    , {
	        "url": "new_news.html",
	        "name": "信息发布",
	        "class": "fa-newspaper-o"
	    }
	    , {
	        "url": "match_manage.html",
	        "name": "比赛管理",
	        "class": "fa-futbol-o"
	    }
	    , {
	        "url": "news_manage.html",
	        "name": "信息管理",
	        "class": "fa-cubes"
	    }
	    ]
    
}
//管理人员
var admin_list = {
    "menu_list": [
	    {
	        "url": "user_manage.html",
	        "name": "人员管理",
	        "class": "fa-user"
	    }, {
	        "url": "team_manage.html",
	        "name": "团队管理",
	        "class": "fa-users"
	    }
	    , {
	        "url": "new_match.html",
	        "name": "创建比赛",
	        "class": "fa-lightbulb-o"
	    }
	    , {
	        "url": "new_news.html",
	        "name": "信息发布",
	        "class": "fa-newspaper-o"
	    }
	    , {
	        "url": "match_manage.html",
	        "name": "比赛管理",
	        "class": "fa-futbol-o"
	    }
	    , {
	        "url": "news_manage.html",
	        "name": "信息管理",
	        "class": "fa-cubes"
	    }
	    ]
    
}
//队长
var chief_list = {
    "menu_list": [
	    {
	        "url": "back_add.html",
	        "name": "队员管理",
	        "class": "fa-users"
	    }, {
	        "url": "back_activities.html",
	        "name": "项目管理",
	        "class": "fa-futbol-o"
	    }
	    ]
    
}
// 指导老师视图
var teacher_list = {
    "menu_list": [
	    {
	        "url": "back_comm.html",
	        "name": "团队交流",
	        "class": "fa-users"
	    }, {
	        "url": "match_manage.html",
	        "name": "项目管理",
	        "class": "fa-futbol-o"
	    }
	    ]
    
}
// 专家视图
var expert_list = {
    "menu_list": [
	    {
	        "url": "back_comm.html",
	        "name": "团队交流",
	        "class": "fa-users"
	    }, {
	        "url": "match_manage.html",
	        "name": "项目管理",
	        "class": "fa-futbol-o"
	    }
	    ]
    
}

//投资人视图
var investor_list = {
    "menu_list": [
	    {
	        "url": "back_add.html",
	        "name": "队员管理",
	        "class": "fa-users"
	    }, {
	        "url": "match_manage.html",
	        "name": "项目管理",
	        "class": "fa-futbol-o"
	    }
	    ]
    
}
//指导老师视图

$.get("my-php/php-lib/get_user_info.php?u_id=0",function(data){
	var user_identity = data["identity"]
	if(!data)
	{
		window.location.href='../index.html'


	}

var bt = baidu.template;

	switch(user_identity)
	{
		case "root":
		var header_temp = bt('left_menu', root_list);
		break;
		case "admin":
		var header_temp = bt('left_menu', admin_list);
		break;
		case "chief":
		var header_temp = bt('left_menu', chief_list);
		break;
		case "expert":
		var header_temp = bt('left_menu', expert_list);
		break;
		case "tutor":
		var header_temp = bt('left_menu', teacher_list);
		break;
		case "inverstor":
		var header_temp = bt('left_menu', inverstor_list);
		break;




	}




// var header_temp = bt('left_menu', menu_list);
$('.side_bar').before(header_temp);

},"json")

