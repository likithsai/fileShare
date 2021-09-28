<?php
    class SessionManager {
        function __construct(){
            return $this->start();
        }

        public function start(){
            session_start();
            return $this;
        }

        function set($name, $value){
            $_SESSION[$name] = $value;
            return $this;
        }

        function get($name ,$def = false){
            if(isset($_SESSION[$name]))
              return $_SESSION[$name];
            else
              return ($def !== false)? $def : false;
        }

        function del($name){
            unset($_SESSION[$name]);
            return $this;
        }

        function destroy(){
            $_SESSION = array();
            session_destroy();
            return $this;
        }

        function fromArray($a=null){
            if( is_array($a) ){
              foreach($a as $k => $v) 
                $this->set($k,$v);
            }
            return $this;
        }

        function __toString(){
            return '<pre>'.print_r($_SESSION,true).'</pre>';
        }
    }
