<?php
    if(C('LAYOUT_ON')) {
        echo '{__NOLAYOUT__}';
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>跳转提示</title>
    <style type="text/css">
        *{ padding: 0; margin: 0; }
        body{ background:#f1f4f5; font-family: '微软雅黑'; color: #333; font-size: 16px; }
        .system-message{ padding: 24px 48px;width: 500px;margin-left:100px;margin-top:100px;text-align: center}
        .system-message h1{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
        .system-message .jump{ padding-top: 10px;margin-bottom: 50px;}
        .system-message .jump a{color: #00b7ee;}
        .system-message .success,.system-message .error{ line-height: 1.8em; font-size: 36px }
        .system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
        #wait{
            display: inline-block;
            height:25px;
            width: 25px;
            border-radius: 25px;
            line-height: 25px;
            border:2px dashed #999;
        }
        .bg_lg{
            height:220px;
            width: 220px;
            line-height: 220px;
            border-radius: 220px;
            margin: 0 auto;
            background: lightcoral;
        }
        h2{
            color: #fff;
            font-size: 100px;
        }
    </style>
</head>
<body>
<div class="system-message">
    <p class="detail"></p>
    <p class="jump">
        系统将在 <b id="wait"><?php echo($waitSecond); ?></b> 秒后自动跳转,如长时间未反应,请点击 <a id="href" href="<?php echo($jumpUrl); ?>">此处跳转</a>
    </p>
    <?php if(isset($message)) {?>
    <p class="success">
        <?php echo($message); ?>
        <div class="bg_lg"><h2>✔</h2></div>
    </p>
    <?php }else{?>
    <p class="error">
        <?php echo($error); ?>
        <div class="bg_lg"><h2>✘</h2></div>
    </p>
    <?php }?>

</div>
<script type="text/javascript">
    (function(){
        var wait = document.getElementById('wait'),href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            }
        }, 1000);
    })();
</script>
</body>
</html>
