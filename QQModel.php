<?php
    class QQModel {
        private $session,$server,$botqq;
        private function fetch ($url, $postdata = null) {
            $ch = curl_init ();
            curl_setopt ($ch, CURLOPT_URL, $url);
            if (!is_null ($postdata)) {
                curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query ($postdata));
            }
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
            $re = curl_exec ($ch);
            curl_close ($ch);
            
            return $re;
        }
        private function callMethod ($method, $param = array (), $detection = true) {
            /** 初始化变量 */
            $url = $this->server . '/' . $method;
        
            /** 访问网页 */
            $ret = json_decode ($this->fetch ($url, $param), true);
            
            /** 分析结果 */
            if ($ret['ok'] == false && $detection == true) {
                if ($ret['error_code'] != 400 && $ret['error_code'] != 403) {
                    $errorModel = new ErrorModel;
                    $errorModel->sendError ('-1001078722237', '尝试调用 ' . $method . " 时出现问题，参数表如下：\n" . print_r ($param, true) . "\n\n返回结果：\n" . print_r ($ret, true));
                }
            }
            
            /** 返回 */
            return $ret;
        }
        private function getSession($authKey)
        {
            $this->session=$this->callMethod('auth',['authKey'=>$authKey]);
            $this->callMethod('verify',['sessionKey'=>$session,'qq'=>$botqq]);
        }
        public function sendMessage ($authKey,$botqq,$server,$master,$text) {
            $this->server=$server;
            $this->$botqq=$botqq;
            $this->ret = $this->callMethod ('sendFriendMessage', [
                "sessionKey"=> $this->$session,"target"=>$master,"messageChain"=>["type"=> "Plain","text"=> $text]
            ]);
            return $this->ret['result']['message_id'];
        }
    }
