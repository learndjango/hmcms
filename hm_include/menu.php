<?php
/**
 * Xử lý menu
 * Vị trí : hm_include/menu.php
 */
if (!defined('BASEPATH'))
    exit('403');
/**
 * Gọi thư viện menu
 */
require_once(BASEPATH . HM_INC . '/menu/hm_menu.php');
/**
 * Khởi tạo class
 */
$hmmenu = new menu;
function get_menu_name($id) {
    $id     = hook_filter('before_get_menu_name', $id);
    $hmdb   = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
    $return = FALSE;
    hook_action('get_menu_name');
    $tableName  = DB_PREFIX . "object";
    $whereArray = array(
        'key' => MySQL::SQLValue('menu'),
        'id' => MySQL::SQLValue($id, MySQL::SQLVALUE_NUMBER)
    );
    $hmdb->SelectRows($tableName, $whereArray);
    $rowCount = $hmdb->RowCount();
    if (is_numeric($rowCount)) {
        $row    = $hmdb->Row();
        $return = $row->name;
    }
    $return = hook_filter('get_menu_name', $return);
    return $return;
}
function get_men_val($args = array()) {
    $args = hook_filter('before_get_men_val', $args);
    global $hmmenu;
    hook_action('get_men_val');
    $return = $hmmenu->get_men_val($args);
    $return = hook_filter('get_men_val', $return);
    return $return;
}
function register_menu_location($args = array()) {
    $args = hook_filter('before_register_menu_location', $args);
    global $hmmenu;
    hook_action('register_menu_location');
    $return = $hmmenu->register_menu_location($args);
    $return = hook_filter('register_menu_location', $return);
    return $return;
}
function menu_location($menu_location_name = NULL) {
    $menu_location_name = hook_filter('before_menu_location', $menu_location_name);
    global $hmmenu;
    hook_action('menu_location');
    $menu_location = $hmmenu->menu_location;
    if (isset($menu_location[$menu_location_name])) {
        $return = $hmmenu->menu_location($menu_location[$menu_location_name]);
        $return = hook_filter('menu_location', $return);
        return $return;
    } else {
        return FALSE;
    }
}
function menu_location_array($menu_location_name = NULL) {
    $menu_location_name = hook_filter('before_menu_location_array', $menu_location_name);
    global $hmmenu;
    hook_action('menu_location_array');
    $menu_location = $hmmenu->menu_location;
    if (isset($menu_location[$menu_location_name])) {
        $return = $hmmenu->menu_location_array($menu_location[$menu_location_name]);
        $return = hook_filter('menu_location_array', $return);
        return $return;
    } else {
        return FALSE;
    }
}
function get_menu($args = array()) {
    $args = hook_filter('before_get_menu', $args);
    global $hmmenu;
    hook_action('get_menu');
    if (is_numeric($args)) {
        $args = array(
            'parent' => $args
        );
    }
    $return = $hmmenu->get_menu($args);
    $return = hook_filter('get_menu', $return);
}
?>
