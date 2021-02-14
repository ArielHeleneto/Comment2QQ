<?php

/**
 * Typecho 评论推送到QQ
 * 
 * @package Comment2QQ
 * @author ArielHeleneto
 * @version 0.2.0
 * @link https://www.starroad.top
 */

require_once('mirai.php');

class Comment2QQ_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Feedback')->finishComment = array('Comment2QQ_Plugin', 'comment_send');
        Typecho_Plugin::factory('Widget_Comments_Edit')->finishComment = array('Comment2QQ_Plugin', 'comment_send');
        Helper::addAction("CommentEdit", "Comment2QQ_Action");
        return _t('请配置相关配置以获得buff加成。');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){
        $authKey = new Typecho_Widget_Helper_Form_Element_Text('authKey', NULL, '1234567890', _t('authKey'), _t('需要输入Mirai后端验证的authKey'));
        $form->addInput($authKey->addRule('required', _t('您必须填写一个正确的authKey')));
        $server = new Typecho_Widget_Helper_Form_Element_Text('server', NULL, 'http://localhost:8080', _t('server'), _t('需要输入Mirai后端地址'));
        $form->addInput($server->addRule('required', _t('您必须填写一个正确的后端地址')));
        $botQQ = new Typecho_Widget_Helper_Form_Element_Text('botQQ', NULL, NULL, _t('botQQ'), _t('需要输入botQQ（即机器人的QQ号）'));
        $form->addInput($botQQ->addRule('required', _t('您必须填写一个正确的botQQ')));
        $Mode = new Typecho_Widget_Helper_Form_Element_Radio('mode', array ('0' => '私聊', '1' => '群聊'), 0, '推送消息对象','');
        $form->addInput($Mode->addRule('enum', _t('必须选择一个模式'), array(0, 1)));
        $masterQQ = new Typecho_Widget_Helper_Form_Element_Text('masterQQ', NULL, NULL, _t('masterQQ'), _t('需要输入推送目标（QQ号或群号）'));
        $form->addInput($masterQQ->addRule('required', _t('您必须填写一个正确的masterQQ')));
    }


    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    public static function comment_send($comment, $post){
        $text = $comment->author . ' 在 "' . $comment->title . '"(#' . $comment->cid . ') 中说到: 
> ' . $comment->text . ' (#' . $comment->coid . ')'."（".($comment->link)."）";
        mirai_push($text);
    }
    
}
