<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

class MiraiException extends Exception {}

/**
 * Mirai 功能类
 */
class Mirai {
    /**
     * 发送请求
     * 
     * @access protected
     * @param string $url
     * @param array $content
     * @throws MiraiException
     * @return array
     */
    protected static function request($url, $content) {
        $cxt = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/json',
                'content' => json_encode($content)
            )
        ));
        $resp = file_get_contents($url, false, $cxt);
        if (!$resp) {
            throw new MiraiException('后端与 Mirai 交互失败');
            return array();
        }
        $resp = json_decode($resp, true);
        if (!$resp) {
            throw new MiraiException('后端与 Mirai 交互失败');
            return array();
        }
        return $resp;
    }

    /**
     * 推送到 Mirai
     * 
     * @access public
     * @param string $text
     * @throws MiraiException
     * @return bool
     */
    public static function push($text) {
        $options = Helper::options()->plugin('Comment2QQ');
        $base = $options->server;
        $resp = self::request("{$base}/auth", array('authKey' => $options->authKey));
        if (!isset($resp['code']) || $resp['code'] !== 0) {
            throw new MiraiException('Mirai 会话认证失败');
            return false;
        }
        $session = $resp['session'];
        $resp = self::request("{$base}/verify", array('sessionKey' => $session, 'qq' => $options->botQQ));
        if (!isset($resp['code']) || $resp['code'] !== 0) {
            throw new MiraiException('Mirai 会话校验失败');
            return false;
        }
        $resp = self::request(
            $base . ($options->mode == 0 ? '/sendFriendMessage' : '/sendGroupMessage'),
            array(
                'sessionKey' => $session,
                'target' => $options->masterQQ,
                'messageChain' => array(0 => array('type' => 'Plain', 'text' => $text))
            )
        );
        if (!isset($resp['code']) || $resp['code'] !== 0) {
            // throw new MiraiException('Mirai 推送失败'); // 不建议在此处抛出异常 建议记录日志
            return false;
        }
        self::request("{$base}/release", array('sessionKey' => $session, 'qq' => $options->botQQ));
        return true;
    }
}
