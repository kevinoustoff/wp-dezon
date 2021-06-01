<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <?php
        wp_head();
        if (isset($_COOKIE['whizz_sound_enable']) && $_COOKIE['whizz_sound_enable'] == 'on') {
            $whizz_sound_text = esc_html__('Sound off', 'whizz-chat');
            $whizz_sound_val = 'off';
        } else { // in case of value off
            $whizz_sound_text = esc_html__('Sound on', 'whizz-chat');
            $whizz_sound_val = 'on';
        }
        $image_id = '';
        ?>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    </head>
    <body class="<?php echo is_rtl() ? ' rtl' : ''; ?> chats-tab-open">
        <div class="main-layout whizzchat-main-layout">
            <div class="navigation navbar navbar-light bg-primary">
                <a class="d-none d-xl-block bg-light rounded p-1" href="<?php echo home_url('/');?>">
                    <img class="avatar-img" src="<?php echo plugin_dir_url('/') . 'whizz-chat/assets/images/whizzchat-logo-dashboard.svg';?>" alt="<?php echo esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE));?>">
                </a>
                <ul class="nav nav-minimal flex-row flex-grow-1 justify-content-between flex-xl-column justify-content-xl-center" id="mainNavTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link p-0 py-xl-3 active" id="chats-tab" href="#chats-content" title="Chats">
                            <svg class="hw-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            <aside class="whizz-sidebar">
                <div class="whizz-tab-content">
                    <div class="tab-pane active" id="chats-content">
                        <div class="d-flex flex-column h-100">
                            <div class="hide-scrollbar h-100" id="chatContactsList">
                                <div class="sidebar-header sticky-top p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <h5 class="font-weight-semibold mb-0"><?php echo esc_html__('Chat List', 'whizz-chat');?></h5>
                                        <ul class="nav flex-nowrap">
                                            <li class="nav-item list-inline-item mr-0">
                                                <div class="dropdown">
                                                    <a class="nav-link text-muted px-1" href="#" role="button" title="Details" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                        </svg>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                                        </svg>
                                                        <?php echo '<a href="javascript:void(0)" class="whizzChat-sound-switch dropdown-item" data-sound-val="' . $whizz_sound_val . '" data-replace-text="' . $whizz_sound_text . '">' . $whizz_sound_text . '</a>';?>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="sidebar-sub-header">
                                        <form class="form-inline">
                                            <div class="input-group">
                                                <input type="text" data-style="dashboard" class="chat-search form-control search transparent-bg pr-0" placeholder="Search users...">
                                                <div class="input-group-append dashb-search-loader"></div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <ul class="contacts-list" id="chatContactTab" data-chat-list="">
                                    <?php
                                    $chat_lists = whizzChat_chat_list();
                                    $chat_list_html = apply_filters('whizzChat_dashboard_load_chatlist', $chat_lists);
                                    echo whizzChat_return($chat_list_html);
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
            <main class="whizz-main">
                <div class="chats">
                    <div class="d-flex flex-column justify-content-center text-center h-100 w-100">
                        <div class="container">
                            <div class="avatar avatar-lg mb-2">
                                <img class="avatar-img" src="<?php echo plugin_dir_url('/') . 'whizz-chat/assets/images/whizzchat-logo-dashboard.svg';?>" alt="<?php echo esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE));?>">
                            </div>
                            <h5><?php echo esc_html__('Welcome to WhizzChat Messenger', 'whizz-chat')?></h5>
                            <p class="text-muted"><?php echo esc_html__('Please select a chat to Start messaging.', 'whizz-chat')?></p>
                        </div>
                    </div>
                </div>
            </main>
            <?php
            global $whizzChat_options;
            $whizzChat_options = get_option('whizz-chat-options');
            $whizzchat_sidebar = isset($whizzChat_options["whizzchat-sidebar"]) && $whizzChat_options["whizzchat-sidebar"] == '1' ? true : false;
            $whizzchat_sidebar_title = isset($whizzChat_options["whizzChat-sidebar-title"]) && $whizzChat_options["whizzChat-sidebar-title"] != '' ? $whizzChat_options["whizzChat-sidebar-title"] : '';

            if ($whizzchat_sidebar) {
                ?>
                <div class="appbar">
                    <!-- Chat Info Start -->
                    <div class="chat-info chat-info-visible">
                        <div class="d-flex h-100 flex-column">
                            <!-- Chat Info Header Start -->
                            <?php if ($whizzchat_sidebar_title != '') {?>
                                <div class="chat-info-header px-2">
                                    <div class="container-fluid">
                                        <ul class="nav justify-content-between align-items-center">
                                            <!-- Sidebar Title Start -->
                                            <li class="text-center">
                                                <h5 class="text-truncate mb-0"><?php echo whizzChat_return($whizzchat_sidebar_title);?></h5>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            <?php }?>
                            <!-- whizzchat widget  -->
                            <div class="whizzchat-sidebar hide-scrollbar">
                                <?php
                                if (is_active_sidebar('whizzchat_sidebar')) {
                                    dynamic_sidebar('whizzchat_sidebar');
                                }
                                ?>
                            </div>
                            <!-- whizzchat widget  -->
                        </div>
                    </div>
                    <!-- Chat Info End -->
                </div>
            <?php }?>
            <div class="backdrop"></div>
        </div>
        <?php wp_footer();?>
    </body>
</html>