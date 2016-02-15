$(function(){
    //邮箱的正则表达式
    var emailReg =  /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,4}$/;
    //身份证的正则表达式
    var idReg = /\d{6,17}[\d|x]|\d{15}/;
    //出生日期的正则表达式
    var birReg = /^\d{4}(\-|\/|.)\d{1,2}\1\d{1,2}$/;
    //邮箱验证
    var email = "";
    var num = "";
    var pwd = "";
    var pwdAgain = "";
    var birth = "";
    var team = "";
    $('#email').blur(function(){
        if($(this).val().length == 0){
            $('#emailTs').html('邮箱不能为空！').css('color','red');
            email = 0;
        }else if(!emailReg.test($(this).val())){
            $('#emailTs').html('邮箱格式不正确，请重新输入！').css('color','red');
            email = 0;
        }else{
            $('#emailTs').html('OK').css('color','#999');
            email = 1;
        }
    });
    //证件号验证
    $('#idNum').blur(function(){
        if($('#idType').val() == "学生证"){
            if($(this).val().length == 0){
                $('#idNumTs').html('证件号不能为空！').css('color','red');
                num = 0;
            }else{
                $('#idNumTs').html('OK').css('color','#999');
                num = 1;
            }
        }else{
            if($(this).val().length == 0){
                $('#idNumTs').html('证件号不能为空！').css('color','red');
                num = 0;
            }else if(!idReg.test($(this).val())){
                $('#idNumTs').html('证件不正确，请重新输入！').css('color','red');
                num = 0;
            }else{
                $('#idNumTs').html('OK').css('color','#999');
                num = 1;
            }
        }
    });
    //密码验证
    $('#pwd').blur(function(){
        if($(this).val().length == 0){
            $('#pwdTs').html('密码不能为空！').css('color','red');
            pwd = 0;
        }else if($(this).val().length < 6){
            $('#pwdTs').html('密码不能小于6位！').css('color','red');
            pwd = 0;
        }else{
            $('#pwdTs').html('OK').css('color','#999');
            pwd = 1;
        }
    });
    //密码确认验证
    $('#pwdAgain').blur(function(){
        if($(this).val().length == 0){
            $('#pwdAgainTs').html('密码确认不能为空！').css('color','red');
            pwdAgain = 0;
        }else if($(this).val().length < 6){
            $('#pwdAgainTs').html('密码确认不能小于6位！').css('color','red');
            pwdAgain = 0;
        }else if($(this).val() != $('#pwd').val()){
            $('#pwdAgainTs').html('确认密码与密码输入不一致，请重新输入！').css('color','red');
            pwdAgain = 0;
        }else{
            $('#pwdAgainTs').html('OK').css('color','#999');
           pwdAgain = 1;
        }
    });
    //出生年月格式
    $('#birthday').blur(function(){
        if(!birReg.test($(this).val())){
            $('#birTs').html('出生日期格式不对，间隔可用空格 或/ 或.').css('color','red');
            birth = 0;
        }else{
            $('#birTs').html('OK').css('color','#999');
            birth = 1;
        }
    });
    //团队名称验证
    $('#teamName').blur(function(){
        if($(this).val().length == 0){
            $('#teamNameTs').html('团队名称不能为空！').css('color','red');
            team = 0;
        }else{
            $('#teamNameTs').html('OK').css('color','#999');
            team = 1;
        }
    });
});
