var header_list={"header_list":[
    {"first_list_href":"front_news_homepage.html",
     "first_list_name":"新闻资讯",
     "second":[
        {"second_list_href":"front_collegeStyle.html","second_list_name":"学院风采"}
          
     ]
    },
    {"first_list_href":"/index.php?m=Home&c=News&a=train",
     "first_list_name":"讲座培训",
     "second":[
        {"second_list_href":"index.php?m=Home&c=News&a=library","second_list_name":"资料库"}
         
     ]
    },
    {"first_list_href":"front_businessPolicy.html",
     "first_list_name":"创业政策",
     "second":[
        {"second_list_href":"front_school.html","second_list_name":"学校"},
          {"second_list_href":"front_place.html","second_list_name":"地方"},
          {"second_list_href":"front_country.html","second_list_name":"国家"}
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
        {"second_list_href":"front_past_activities.html","second_list_name":"往期活动"}
         
     ]
    }


]};



var footer_list={
    "footer_list":{
        "second":[
        {"second_list_href":"javascript:void(0);","second_list_name":"关于我们"},
        {"second_list_href":"javascript:void(0);","second_list_name":"四川大学"},
        {"second_list_href":"javascript:void(0);","second_list_name":"学堂在线"}
         ],
        "third":[
            {"third_list_href":"javascript:void(0);","third_list_name":"36氪/关注互联网创业"},
            {"third_list_href":"javascript:void(0);","third_list_name":"清华X-LAB"},
            {"third_list_href":"javascript:void(0);","third_list_name":"四川省科学技术厅"}
        ]
    }


};


//使用baidu.template命名空间
var bt_1=baidu.template;


var header_temp=bt_1('header_temp',header_list);
$('.navContent').html(header_temp);

var bt_2=baidu.template;

var footer_temp=bt_2('footer_temp',footer_list);
$('#f_center').html(footer_temp);