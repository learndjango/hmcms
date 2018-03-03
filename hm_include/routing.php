<?php
/**
 * Xử lý routing
 * Vị trí : hm_include/routing.php
 */
if (!defined('BASEPATH'))
    exit('403');
/** Lấy đường link theo type và id */
function request_uri($args) {
    $hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
    if (!is_array($args)) {
        parse_str($args, $args);
    }
    $type       = $args['type'];
    $id         = $args['id'];
    $tableName  = DB_PREFIX . "request_uri";
    $whereArray = array(
        'object_type' => MySQL::SQLValue($type),
        'object_id' => MySQL::SQLValue($id, MySQL::SQLVALUE_NUMBER)
    );
    $hmdb->SelectRows($tableName, $whereArray);
    if ($hmdb->HasRecords()) {
        $row      = $hmdb->Row();
        $uri      = $row->uri;
        $full_uri = BASE_URL . $uri;
        return $full_uri;
    } else {
        return FALSE;
    }
}
function ahref($args) {
    $hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
    if (!is_array($args)) {
        parse_str($args, $args);
    }
    $type  = $args['type'];
    $id    = $args['id'];
    $link  = request_uri(array(
        'type' => $type,
        'id' => $id
    ));
    $ref   = '';
    $title = '';
    switch ($type) {
        case 'content':
            $ref   = ' ref="bookmark" ';
            $title = get_primary_con_val($id);
            break;
        case 'taxonomy':
            $ref   = ' ref="category tag" ';
            $title = get_primary_tax_val($id);
            break;
    }
    if (isset($args['text'])) {
        return '<a href="' . $link . '" title="' . $title . '" ' . $ref . '>' . $args['text'] . '</a>';
    } else {
        return '<a href="' . $link . '" title="' . $title . '" ' . $ref . '>';
    }
}
function get_uri_data($args) {
    $hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
    if (!is_array($args)) {
        parse_str($args, $args);
    }
    if (isset($args['uri'])) {
        $uri = $args['uri'];
    } else {
        $uri = FALSE;
    }
    if (isset($args['id'])) {
        $id_uri = $args['id'];
    } else {
        $id_uri = FALSE;
    }
    $tableName = DB_PREFIX . "request_uri";
    if (is_numeric($id_uri)) {
        $whereArray = array(
            'id' => MySQL::SQLValue($id_uri)
        );
    } else {
        $whereArray = array(
            'uri' => MySQL::SQLValue($uri)
        );
    }
    $hmdb->SelectRows($tableName, $whereArray);
    if ($hmdb->HasRecords()) {
        $row = $hmdb->Row();
        return $row;
    } else {
        return FALSE;
    }
}
function get_current_uri() {
    $_SERVER['REQUEST_URI_PATH'] = parse_url(getenv('REQUEST_URI'), PHP_URL_PATH);
    if (FOLDER_PATH != '/') {
        $request_slug = str_replace(FOLDER_PATH, '', $_SERVER['REQUEST_URI_PATH']);
    } else {
        $request_slug = substr($_SERVER['REQUEST_URI_PATH'], 1);
    }
    $request_slug = urldecode($request_slug);
    $last         = substr($request_slug, -1);
    if ($last == '/') {
        $request_slug = substr($request_slug, 0, -1);
    }
    /** Kiểm tra phân trang */
    $matches     = null;
    $returnValue = preg_match('/' . PAGE_BASE . '([0-9]+)/', $request_slug, $matches);
    if ($returnValue == '1') {
        $page_string = $matches[0];
        $page_number = $matches[1];
        if ($request_slug == $page_string) {
            $request_slug = '';
        } else {
            $request_slug = str_replace('/' . $page_string, '', $request_slug);
        }
    }
    return $request_slug;
}
function get_current_pagination($request_slug = FALSE) {
    if (!$request_slug) {
        $_SERVER['REQUEST_URI_PATH'] = parse_url(getenv('REQUEST_URI'), PHP_URL_PATH);
        if (FOLDER_PATH != '/') {
            $request_slug = str_replace(FOLDER_PATH, '', $_SERVER['REQUEST_URI_PATH']);
        } else {
            $request_slug = substr($_SERVER['REQUEST_URI_PATH'], 1);
        }
        $request_slug = urldecode($request_slug);
    }
    $matches     = null;
    $returnValue = preg_match('/' . PAGE_BASE . '([0-9]+)/', $request_slug, $matches);
    if ($returnValue == '1') {
        $page_number = $matches[1];
        return $page_number;
    } else {
        return FALSE;
    }
}
?>
