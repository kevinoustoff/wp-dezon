<?php
/*
  Create Database Table
 */

class Whizz_chat_db_tables {

    public static function whizz_create_db_tables() {

        global $wpdb;
        global $whizz_tbl_sessions;
        global $whizz_tblname_chat_message;
        global $whizz_tblname_chat_ratings;
        global $whizz_tbl_user_preferences;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $sql_sessions = "
        CREATE TABLE " . $whizz_tbl_sessions . " (
        `id` int (11) NOT NULL AUTO_INCREMENT,
        `timestamp` datetime ,
        `name` varchar (255),
        `email` varchar (255),
        `ip` varchar (255),
        `status` int (11),
        `session` varchar (300),
        `url` varchar (255),
        `last_active_timestamp` datetime ,
        `agent_id` int (11),
        `other` text ,
        `rel` varchar (120),
        `chat_box_id` varchar (300),
        `sender_id` int (11),
        `chat_box_status` int (11),
        `chatbox_sender_open` int (11)  DEFAULT 1,
        `chatbox_receiver_open` int (11) DEFAULT 1,
        `message_type` varchar (300),
        `message_for` varchar (255),
        `message_count` int (11),        
         PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

        maybe_create_table($whizz_tbl_sessions, $sql_sessions);

        $sql_chat_message = '
        CREATE TABLE ' . $whizz_tblname_chat_message . ' (
          `id` int (11) NOT NULL AUTO_INCREMENT,
          `session_id` int (11),
          `fromname` varchar (255),
          `message` blob ,
          `timestamp` datetime ,
          `status` int (3),
          `extra` text ,
          `rel` varchar (120),
          `post_id` int (10),
          `author_id` int (10),
          `is_reply` int (1),
          `message_type` varchar (300),
          `attachments` varchar (100),
          `seen_at` datetime,
           PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
    ';

        maybe_create_table($whizz_tblname_chat_message, $sql_chat_message);

        $sql_preferences = "CREATE TABLE {$whizz_tbl_user_preferences} (
        `id` int (11) NOT NULL AUTO_INCREMENT,
        `blocker_id` varchar (765),
        `blocked_id` varchar (765),
        `post_id` int (11),
         PRIMARY KEY (id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

        maybe_create_table($whizz_tbl_user_preferences, $sql_preferences);
    }

}

new Whizz_chat_db_tables();