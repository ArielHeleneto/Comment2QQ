<?php
/*
插件的配置页面，不需要填写。
*/
// 以下参数为主参数
// mirai推送地址 *单引号

$_cfg = Helper::options()->plugin('Comment2QQ');

$mirai_main_url = $_cfg->server;
// mirai机器人qq *无需引号
$miraiid = $_cfg->botQQ;
// mirai认证密钥 *单引号
$miraikey = $_cfg->authKey;
// 推送消息对象 私聊'/sendFriendMessage'，群聊'/sendGroupMessage'
if($_cfg->mode==0){
    $mirai_push_url = $mirai_main_url.'/sendFriendMessage';
}
else{
    $mirai_push_url = $mirai_main_url.'/sendGroupMessage';
}
// 需要推送的用户/群聊号码 *无需引号
$mirai_push_id = $_cfg->masterQQ;

// 以下参数无需手动填写
$mirai_auth_url = $mirai_main_url.'//auth';
$mirai_verify_url = $mirai_main_url.'//verify';
$mirai_release_url = $mirai_main_url.'//release';