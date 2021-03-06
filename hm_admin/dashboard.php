<?php
/**
 * Tệp tin bảng quản trị của admin
 * Vị trí : admin/dashboard.php
 */
if (!defined('BASEPATH'))
    exit('403');
/** gọi tệp tin admin base */
require_once(dirname(__FILE__) . '/admin.php');
/** gọi model xử lý dashboard */
require_once(dirname(__FILE__) . '/dashboard/dashboard_model.php');
require_once(dirname(__FILE__) . '/update/update_model.php');
require_once(dirname(__FILE__) . '/plugin/plugin_model.php');
require_once(dirname(__FILE__) . '/theme/theme_model.php');
/** check quyền của user */
$session_admin_login = json_decode(hm_decode_str($_SESSION['admin_login']), TRUE);
$user_id             = $session_admin_login['user_id'];
user_role_redirect($user_id, 'dashboard');
/** Check update */
$args['update_noti']  = '';
$check_update_session = FALSE;
if (isset($_SESSION['check_update_session'])) {
    $check_update_session = $_SESSION['check_update_session'];
}
if ($check_update_session == FALSE) {
    $check_url = HM_GIT_RAW . '/.version';
    @$content = file_get_contents($check_url);
    $content = json_decode($content, TRUE);
    if (is_array($content)) {
        $args['update_noti'] = '';
        if (ALLOW_UPDATE == TRUE) {
            if (isset($content['newest']) AND $content['newest'] == HM_VERSION) {
                $args['update_noti'] = '<div class="alert alert-success" role="alert">Bạn đang cài đặt phiên bản mới nhất</div>';
            } else {
                $args['update_noti'] = '<div class="alert alert-danger" role="alert">Đã có phiên bản mới, bạn có thể cập nhật hoặc truy cập hoamaisoft.com để tải về</div>';
            }
        }
        if (isset($content['newest']) AND $content['newest'] != HM_VERSION) {
            if (isset($content['required']) AND $content['required'] == '1') {
                update_core();
            }
        }
    } else {
        $args['update_noti'] = '<div class="alert alert-danger" role="alert">Mất kết nối đến máy chủ cập nhật</div>';
    }
    $_SESSION['check_update_session'] = TRUE;
}
/** Hiển thị giao diện dashboard */
function admin_content_page() {
    global $args;
    hook_action('admin_content_page_dashboard_before');
    require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'dashboard.php');
    hook_action('admin_content_page_dashboard_after');
}
/** fontend */
hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');
?>
