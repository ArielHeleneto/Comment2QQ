<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * Typecho 评论推送到QQ
 * 
 * @package Comment2QQ
 * @author ArielHeleneto, wuxianucw
 * @version 0.3.0
 * @link https://www.starroad.top
 */
class Comment2QQ_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate() {
        Typecho_Plugin::factory('Widget_Feedback')->finishComment = array('Comment2QQ_Plugin', 'commentSend');
        Typecho_Plugin::factory('Widget_Comments_Edit')->finishComment = array('Comment2QQ_Plugin', 'commentSend');
        Helper::addAction('CommentEdit', 'Comment2QQ_Action');
        return _t('请完成插件配置以开始正常使用');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate() {}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form) {
        $authKey = new Typecho_Widget_Helper_Form_Element_Text('authKey', NULL, '', _t('authKey'), _t('Mirai 后端验证的 authKey'));
        $form->addInput($authKey->addRule('required', _t('您必须填写一个正确的 authKey')));
        $server = new Typecho_Widget_Helper_Form_Element_Text('server', NULL, 'http://localhost:8080',
            _t('Mirai 后端地址'), _t('Mirai HTTP API 监听的后端地址'));
        $form->addInput($server->addRule('required', _t('您必须填写一个正确的后端地址')));
        $botQQ = new Typecho_Widget_Helper_Form_Element_Text('botQQ', NULL, '', _t('机器人 QQ'), _t('机器人的 QQ号'));
        $form->addInput($botQQ->addRule('required', _t('您必须填写一个正确的机器人 QQ')));
        $Mode = new Typecho_Widget_Helper_Form_Element_Radio('mode', array(0 => '私聊', 1 => '群聊'), 0, '推送消息对象', '');
        $form->addInput($Mode->addRule('enum', _t('必须选择一个推送消息对象'), array(0, 1)));
        $masterQQ = new Typecho_Widget_Helper_Form_Element_Text('masterQQ', NULL, NULL, _t('目标 ID'), _t('推送目标的号码（QQ号或群号）'));
        $form->addInput($masterQQ->addRule('required', _t('您必须填写一个正确的目标 ID')));
    }


    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form) {}

    public static function commentSend($comment, $post) {
        require_once __DIR__ . '/Mirai.php';
        $text = $comment->author . ' 在 "' . $comment->title . '"(#' . $comment->cid . ') 中说到: 
> ' . $comment->text . ' (#' . $comment->coid . ')'."（".($comment->link)."）";
        Mirai::push($text);
    }
}
