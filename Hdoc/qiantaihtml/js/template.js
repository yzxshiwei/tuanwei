var header_list={"header_list":[
    {"first_list_href":"front_news_homepage.html",
     "first_list_name":"新闻资讯",
     "second":[
        {"second_list_href":"front_collegeStyle.html","second_list_name":"学院风采"}
          
     ]
    },
    {"first_list_href":"front_training.html",
     "first_list_name":"讲座培训",
     "second":[
        {"second_list_href":"front_library.html","second_list_name":"资料库"}
         
     ]
    },
    {"first_list_href":"front_businessPolicy.html",
     "first_list_name":"创业政策",
     "second":[
        {"second_list_href":"#","second_list_name":"学校"},
          {"second_list_href":"#","second_list_name":"地方"},
          {"second_list_href":"#","second_list_name":"国家"}
     ]
    },
    {"first_list_href":"front_team_create.html",
     "first_list_name":"团队组建",
     "second":[
        
     ]
    },
    {"first_list_href":"front_match_information.html",
     "first_list_name":"比赛信息",
     "second":[
        {"second_list_href":"front_past_activities.html","second_list_name":"往期活动"},
         
     ]
    }


]};


var footer_list={"footer_list":[
    {
        "second":[
        {
        "second_url":"javascript:void(0)",
            "second_name":"链接"
        },{
        "second_url":"http://www.scu.edu.cn",
            "second_name":"四川大学"
        }      
         ]
    }
, {
        "second":[ {
        "second_url":"javascript:void(0)",
            "second_name":"链接"
        },{
        "second_url":"http://www.xuetangx.com",
            "second_name":"学堂在线"
        }
                 
         ]
    }
, {
        "second":[
             {
        "second_url":"javascript:void(0)",
            "second_name":"链接"
        },{
        "second_url":"http://36kr.com",
            "second_name":"36氪"
        }
                 
         ]
    }
, {
        "second":[ {
        "second_url":"javascript:void(0)",
            "second_name":"链接"
        },{
        "second_url":"http://www.x-lab.tsinghua.edu.cn",
            "second_name":"清华X-LAB"
        }        
         ]
    }
, {
        "second":[
             {
        "second_url":"javascript:void(0)",
            "second_name":"链接"
        },{
        "second_url":"http://weixin.tccxfw.com",
            "second_name":"四川省科学技术厅"
        }
                 
         ]
    }

]}


//使用baidu.template命名空间
var bt_1=baidu.template;


var header_temp=bt_1('header_temp',header_list);
$('.navContent').html(header_temp);

var bt_2=baidu.template;
var footer_temp=bt_2('footer_temp',footer_list);

$('.footer-bag').html(footer_temp);