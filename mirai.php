<?php
/*
插件内容推送代码，请勿随意修改，更改配置请修改config.php
*/
// 获取推送文字部分
function mirai_push($text) { //发送的内容
    // 手动更新session，并加载参数
    // 加载认证参数
    require ('config.php');
    // mirai会话开始-认证-获取session
    // 组合POST数据
    $mirai_auth_data = json_encode(array('authKey' => $miraikey));
    $mirai_auth_opts = array('http' => array('method' => 'POST', 'header' => 'Content-type: application/json', 'content' => $mirai_auth_data));
    $mirai_auth_context = stream_context_create($mirai_auth_opts);
    // 发送对mirai的POST请求
    $mirai_auth_return = file_get_contents($mirai_auth_url, false, $mirai_auth_context);
    // 截取session
    $mirai_push_session = substr($mirai_auth_return, 21, 8);
    // mirai会话校验
    // 组合POST数据
    $mirai_verify_data = json_encode(array('sessionKey' => "$mirai_push_session", 'qq' => $miraiid));
    $mirai_verify_opts = array('http' => array('method' => 'POST', 'header' => 'Content-type: application/json', 'content' => $mirai_verify_data));
    $mirai_verify_context = stream_context_create($mirai_verify_opts);
    // 发送对mirai的POST请求
    $mirai_verify_return = file_get_contents($mirai_verify_url, false, $mirai_verify_context);
    // 以下为mirai推送
    // 数据转化为json格式
    $mirai_postdata = json_encode(array('sessionKey' => $mirai_push_session, 'target' => $mirai_push_id, 'messageChain' => array(0 => array('type' => 'Plain', 'text' => $text)))); //此处可自行添加指令，如at全体成员
    // 组合POST数据
    $mirai_opts = array('http' => array('method' => 'POST', 'header' => 'Content-type: application/json', 'content' => $mirai_postdata));
    $mirai_context = stream_context_create($mirai_opts);
    // 发送对mirai的POST请求
    $mirai_result = file_get_contents($mirai_push_url, false, $mirai_context);
}
?>