<?php
/**
 * Tệp tin xử lý trình đơn trong admin
 * Vị trí : admin/menu.php
 */
if (!defined('BASEPATH'))
    exit('403');
if (!defined('ALLOW_MENU_PAGE')) {
    define('ALLOW_MENU_PAGE', TRUE);
}
if (ALLOW_MENU_PAGE != TRUE) {
    hm_exit('Tính năng đã bị tắt');
}
/** gọi tệp tin admin base */
require_once(dirname(__FILE__) . '/admin.php');
/** gọi model xử lý menu */
require_once(dirname(__FILE__) . '/menu/menu_model.php');
require_once(dirname(__FILE__) . '/taxonomy/taxonomy_model.php');
/** check quyền của user */
$session_admin_login = json_decode(hm_decode_str($_SESSION['admin_login']), TRUE);
$user_id             = $session_admin_login['user_id'];
user_role_redirect($user_id, 'menu');
$key    = hm_get('key');
$id     = hm_get('id');
$action = hm_get('action');
switch ($action) {
    case 'edit':
        if (isset_menu_id($id)) {
            $args_tax  = $hmtaxonomy->hmtaxonomy;
            $args_menu = get_menu_item($id);
            /** Hiển thị giao diện chỉnh sửa menu */
            function admin_content_page() {
                global $args_tax;
                global $args_menu;
                require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'menu_edit.php');
            }
        } else {
            /** Hiển thị giao diện 404 */
            function admin_content_page() {
                require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_404.php');
            }
        }
        break;
    case 'location':
        /** Hiển thị giao diện menu location */
        function admin_content_page() {
            global $hmmenu;
            require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'menu_location.php');
        }
        break;
    default:
        /** Hiển thị giao diện thêm menu */
        function admin_content_page() {
            require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'menu_choise.php');
        }
}
/** fontend */
hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');
?>