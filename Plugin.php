<?php

/**
 * Typecho 评论推送到QQ
 * 
 * @package Comment2QQ
 * @author ArielHeleneto
 * @version 0.1.0
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
    public static function config(Typecho_Widget_Helper_Form $form){}

    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
    
}
