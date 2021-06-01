<?php
/*
 * Initialization functions of whizz chat
 */

class whizzChat {

    public static function ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            $ip = "0";
        }
        return sanitize_text_field($ip);
    }

    public static function cookie_id() {

        $user_id = get_current_user_id();
        if ($user_id) {
            return $user_id;
        } else {
            $name = isset($_COOKIE['whizzChat_name']) && $_COOKIE['whizzChat_name'] != '' ? $_COOKIE['whizzChat_name'] : '';
            if ($name != '') {
                $cookie_name = 'whizchat-' . str_replace(' ', '-', $name);
                return (isset($_COOKIE[$cookie_name])) ? $_COOKIE[$cookie_name] : "";
            }
        }
    }

    public static function session_id() {
        global $whizzChat_options;

        $user_type = whizzChat_globalVal('whizzChat-chat-type');
        $user_type = (isset($user_type)) ? $user_type : 1;

        $get_session_id = self::get_session_id();
        if ($get_session_id != "") {
            return $get_session_id;
        } else {
            if ($user_type == 0) {

                return $_SESSION['whizz_session_id'] = self::cookie_id();
            } else if ($user_type == 1) {
                return (isset($_SESSION['whizz_session_id']) ) ? $_SESSION['whizz_session_id'] : '';
            } else if ($user_type == 2) {
                return self::get_session_id();
            }
        }
    }

    public static function get_session_id() {
        $session_value = '';



        if (is_user_logged_in()) {

            $user = wp_get_current_user();
            $user_name = $user->display_name;
            $user_email = $user->user_email;

            self::user_name($user_name);
            self::user_email($user_email);

            $session_value = $_SESSION['whizz_session_id'] = self::cookie_id();
        } else {

            $session_value = $_SESSION['whizz_session_id'] = self::cookie_id();
        }

        return $session_value;
    }

    public static function user_agent() {
        return ( $_SERVER['HTTP_USER_AGENT'] );
    }

    public static function random_code() {
        return rand(0, 99) . rand(0, 999) . time() . rand(0, 9999) * rand(0, 99999);
    }

    public static function readable_time($time = '') {
        return date("F j, Y, g:i a", strtotime($time));
    }

    public static function user_name($name = '') {

        ob_start();
        if (is_user_logged_in()) {

            $user = wp_get_current_user();
            $user_name = $user->display_name;

            echo whizzChat_return($user_name);
        } else {
            if ($name != "") {
                if (isset($_COOKIE['whizzChat_name'])) {
                    
                } else {
                    setcookie("whizzChat_name", $name, time() + 31556926, COOKIEPATH, COOKIE_DOMAIN, 0, 0);
                }
                echo whizzChat_return($name);
            } else {
                echo isset($_COOKIE['whizzChat_name']) ? $_COOKIE['whizzChat_name'] : "";
            }
        }
        $set_cookie_data = ob_get_contents();
        ob_end_clean();
        return $set_cookie_data;
    }

    public static function user_email($email = '') {

        ob_start();

        if (is_user_logged_in()) {

            $user = wp_get_current_user();
            $user_email = $user->user_email;

            echo whizzChat_return($user_email);
        } else {
            if ($email != "") {
                if (isset($_COOKIE['whizzChat_email'])) {
                    
                } else {
                    setcookie("whizzChat_email", $name, time() + 31556926, COOKIEPATH, COOKIE_DOMAIN, 0, 0);
                }
                echo whizzChat_return($email);
            } else {
                echo isset($_COOKIE['whizzChat_email']) ? $_COOKIE['whizzChat_email'] : "";
            }
        }

        $set_cookie_data = ob_get_contents();
        ob_end_clean();
        return $set_cookie_data;
    }

    public static function user_data($key = "") {
        $id = get_current_user_id();
        $name = self::user_name();
        $email = self::user_email();
        $data = array("id" => $id, "name" => $name, "email" => $email);
        return ( $key != "" ) ? $data["$key"] : $data;
    }

    public static function user_online_status($user = "", $post_author_id = "") {
        $user_status = '';
        if ($user) {
            $online_user = ($post_author_id == self::session_id()) ? $user : $post_author_id;
            $user_status = get_transient("whizzChat_online_status_user_$online_user");
        }
        return $user_status;
    }

    public static function whizzchat_time_ago($timestamp) {

        $time_ago = strtotime($timestamp);
        $current_time = time(); //current_time();

        $time_difference = $current_time - $time_ago;
        $seconds = $time_difference;
        $minutes = round($seconds / 60); // value 60 is seconds  
        $hours = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  
        $days = round($seconds / 86400); //86400 = 24 * 60 * 60;  
        $weeks = round($seconds / 604800); // 7*24*60*60;  
        $months = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
        $years = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60
        if ($seconds <= 60) {
            return esc_html__("Just Now", "whizz-chat");
        } else if ($minutes <= 60) {
            if ($minutes == 1) {
                return esc_html__("1 minute ago", "whizz-chat");
            } else {
                return "$minutes " . esc_html__("minutes ago", "whizz-chat");
            }
        } else if ($hours <= 24) {
            if ($hours == 1) {
                return esc_html__("an hour ago", "whizz-chat");
            } else {
                return "$hours " . esc_html__("hrs ago", "whizz-chat");
            }
        } else if ($days <= 7) {
            if ($days == 1) {
                return esc_html__("Yesterday", "whizz-chat");
            } else {
                return "$days " . esc_html__("days ago", "whizz-chat");
            }
        } else if ($weeks <= 4.3) {
            if ($weeks == 1) {
                return esc_html__("a week ago", "whizz-chat");
            } else {
                return "$weeks " . esc_html__("weeks ago", "whizz-chat");
            }
        } else if ($months <= 12) {
            if ($months == 1) {
                return esc_html__("a month ago", "whizz-chat");
            } else {
                return "$months " . esc_html__("months ago", "whizz-chat");
            }
        } else {
            if ($years == 1) {
                return esc_html__("one year ago", "whizz-chat");
            } else {
                return "$years " . esc_html__("years ago", "whizz-chat");
                ;
            }
        }
    }

    public static function getTheDay($date) {
        $curr_date = strtotime(date("Y-m-d H:i:s"));
        $the_date = strtotime($date);
        $diff = floor(($curr_date - $the_date) / (60 * 60 * 24));
        switch ($diff) {
            case 0:
                return esc_html__("Today", "whizz-chat") . " " . date("g:i A", $the_date);
                break;
            case 1:
                return esc_html__("Yesterday", "whizz-chat") . " " . date('g:i A', strtotime("-1 days"));
                ;
                break;
            default:
                return date("F jS, Y H:s", $the_date);
        }
    }

}

new whizzChat();