<?php
/**
 * Class này xử lý các menu
 */
if (!defined('BASEPATH'))
    exit('403');
$menu_location = array();
Class menu extends MySQL {
    public $menu_location = array();
    /** Đăng ký 1 vị trí menu */
    public function register_menu_location($input = NULL) {
        if ($input == NULL)
            exit('Missing argument for register_menu_location ');
        if (is_array($input)) {
            return $this->register_menu_location_by_array($input);
        }
    }
    /** Đăng ký 1 vị trí menu bằng cách truyền vào 1 array */
    public function register_menu_location_by_array($args = array()) {
        if ($args['name'] AND $args['nice_name']) {
            if (!$this->isset_menu_location($args)) {
                $this->set_menu_location($args);
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
    /** Trả về tổng số vị trí menu */
    public function total_menu_location() {
        return sizeof($this->menu_location);
    }
    /** kiểm tra vị trí menu đã tồn tại */
    public function isset_menu_location($args = array()) {
        if (is_array($args)) {
            if ($this->total_menu_location() > 0) {
                $all_menu_location        = $this->menu_location;
                $input_menu_location_name = $args['name'];
                if (isset($all_menu_location[$input_menu_location_name])) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
    /** Thêm vị trí menu đã đăng ký vào biến $menu_location */
    private function set_menu_location($args = array()) {
        $this->menu_location[$args['name']] = $args;
    }
    /** Lấy giá trị 1 field của menu */
    public function get_men_val($args = array()) {
        $name       = $args['name'];
        $id         = $args['id'];
        $tableName  = DB_PREFIX . "field";
        $whereArray = array(
            'object_id' => $id,
            'name' => MySQL::SQLValue($name),
            'object_type' => MySQL::SQLValue('menu_item')
        );
        $this->SelectRows($tableName, $whereArray);
        if ($this->HasRecords()) {
            $row = $this->Row();
            return $row->val;
        } else {
            return NULL;
        }
    }
    /** Hiển thị menu theo vị trí đã đăng ký */
    public function menu_location($args = array()) {
        return $this->get_menu($args);
    }
    /** Return menu dưới dạng array theo vị trí */
    public function menu_location_array($args = array()) {
        if (is_array($args)) {
            $name    = $args['name'];
            $menu_id = get_option('section=theme_setting&key=' . $name);
        } elseif (is_numeric($args)) {
            $menu_id = $args;
        }
        if (is_numeric($menu_id)) {
            $return     = array();
            /** load menu item */
            $tableName  = DB_PREFIX . "object";
            $whereArray = array(
                'parent' => MySQL::SQLValue($menu_id),
                'key' => MySQL::SQLValue('menu_item')
            );
            $this->SelectRows($tableName, $whereArray, NULL, 'order_number', 'ASC');
            while ($row = $this->Row()) {
                $menu_item[] = $row;
            }
            /** menu item */
            foreach ($menu_item as $this_menu_item) {
                $id                                 = $this_menu_item->id;
                $return[$id]['id']                  = $id;
                $return[$id]['menu_name']           = get_men_val(array(
                    'name' => 'menu_name',
                    'id' => $id
                ));
                $return[$id]['menu_class']          = get_men_val(array(
                    'name' => 'menu_class',
                    'id' => $id
                ));
                $return[$id]['menu_attr']           = get_men_val(array(
                    'name' => 'menu_attr',
                    'id' => $id
                ));
                $return[$id]['menu_icon']           = get_men_val(array(
                    'name' => 'menu_icon',
                    'id' => $id
                ));
                $menu_request_uri                   = get_men_val(array(
                    'name' => 'menu_request_uri',
                    'id' => $id
                ));
                $return[$id]['menu_request_uri_id'] = $menu_request_uri;
                $return[$id]['menu_request_uri']    = array();
                /** item request uri */
                if (is_numeric($menu_request_uri)) {
                    $data_uri                                                 = get_uri_data("id=$menu_request_uri");
                    $object_type                                              = $data_uri->object_type;
                    $object_id                                                = $data_uri->object_id;
                    $href                                                     = SITE_URL . FOLDER_PATH . $data_uri->uri;
                    $return[$id]['href']                                      = $href;
                    $return[$id]['menu_request_uri']['href']                  = $href;
                    $return[$id]['menu_request_uri']['object_type']           = $object_type;
                    $return[$id]['menu_request_uri']['object_id']             = $object_id;
                    $return[$id]['menu_request_uri']['menu_request_uri_html'] = 'href="' . $href . '"';
                    $return[$id]['menu_request_uri']['data_object_html']      = 'data-' . $object_type . '="' . $object_id . '"';
                } else {
                    $return[$id]['href']                                      = $menu_request_uri;
                    $return[$id]['menu_request_uri']['href']                  = $menu_request_uri;
                    $return[$id]['menu_request_uri']['object_type']           = FALSE;
                    $return[$id]['menu_request_uri']['object_id']             = FALSE;
                    $return[$id]['menu_request_uri']['menu_request_uri_html'] = 'href="' . $menu_request_uri . '"';
                    $return[$id]['menu_request_uri']['data_object_html']      = '';

                }
                /** sub menu */
                if ($this->has_sub_menu($id)) {
                    $return[$id]['has_sub']  = TRUE;
                    $return[$id]['sub_menu'] = $this->menu_location_array($id);
                } else {
                    $return[$id]['has_sub'] = FALSE;
                }
            }
            return $return;
        } else {
            return FALSE;
        }
    }
    /** Hiển thị 1 menu */
    public function get_menu($args = array()) {
        /** kiếm tra và add giá trị mặc định */
        $default_array      = array(
            'parent' => FALSE,
            'wrapper' => 'ul',
            'wrapper_class' => FALSE,
            'wrapper_id' => FALSE,
            'item' => 'li',
            'item_class' => '',
            'item_id' => '',
            'sub_menu_class' => '',
            'permalink_class' => '',
            'permalink_attr' => '',
            'permalink_before' => '',
            'permalink_after' => '',
            'current_page_class' => 'current_page',
            'has_sub_class' => 'has-sub',
            'echo' => TRUE
        );
        $args               = hm_parse_args($args, $default_array);
        $parent             = $args['parent'];
        $wrapper            = $args['wrapper'];
        $wrapper_class      = $args['wrapper_class'];
        $wrapper_id         = $args['wrapper_id'];
        $item               = $args['item'];
        $item_class         = $args['item_class'];
        $item_id            = $args['item_id'];
        $sub_menu_class     = $args['sub_menu_class'];
        $permalink_class    = $args['permalink_class'];
        $permalink_attr     = $args['permalink_attr'];
        $permalink_before   = $args['permalink_before'];
        $permalink_after    = $args['permalink_after'];
        $current_page_class = $args['current_page_class'];
        $has_sub_class      = $args['has_sub_class'];
        $echo               = $args['echo'];
        if ($wrapper == '') {
            $wrapper = 'ul';
        }
        if ($item == '') {
            $item = 'li';
        }
        $menu_item = array();
        $name      = $args['name'];
        if (is_numeric($parent)) {
            $menu_id = $parent;
        } else {
            $menu_id = get_option('section=theme_setting&key=' . $name);
        }
        $output_html = '';
        /** wrapper_class */
        if ($wrapper_class) {
            $wrapper_class = ' class="' . $wrapper_class . '"';
        }
        /** wrapper_id */
        if ($wrapper_id) {
            $wrapper_id = ' id="' . $wrapper_id . '"';
        }
        /** open wrapper */
        $output_html .= '<' . $wrapper . $wrapper_class . $wrapper_id . '>' . "\n";
        /** load menu item */
        $tableName  = DB_PREFIX . "object";
        $whereArray = array(
            'parent' => MySQL::SQLValue($menu_id),
            'key' => MySQL::SQLValue('menu_item')
        );
        $this->SelectRows($tableName, $whereArray, NULL, 'order_number', 'ASC');
        while ($row = $this->Row()) {
            $menu_item[] = $row;
        }
        /** menu item */
        if (sizeof($menu_item) > 0) {
            foreach ($menu_item as $this_menu_item) {
                $id               = $this_menu_item->id;
                $menu_name        = get_men_val(array(
                    'name' => 'menu_name',
                    'id' => $id
                ));
                $menu_class       = get_men_val(array(
                    'name' => 'menu_class',
                    'id' => $id
                ));
                $menu_attr        = get_men_val(array(
                    'name' => 'menu_attr',
                    'id' => $id
                ));
                $menu_icon        = get_men_val(array(
                    'name' => 'menu_icon',
                    'id' => $id
                ));
                $menu_request_uri = get_men_val(array(
                    'name' => 'menu_request_uri',
                    'id' => $id
                ));
                if ($menu_attr != '') {
                    $menu_attr = ' ' . $menu_attr;
                }
                if ($menu_class != '') {
                    $menu_class = ' ' . $menu_class;
                }
                /** item href */
                if (is_numeric($menu_request_uri)) {
                    $data_uri              = get_uri_data("id=$menu_request_uri");
                    $href                  = SITE_URL . FOLDER_PATH . $data_uri->uri;
                    $object_type           = $data_uri->object_type;
                    $object_id             = $data_uri->object_id;
                    $menu_request_uri_html = ' href="' . $href . '"';
                    $data_object_html      = ' data-' . $object_type . '="' . $object_id . '"';
                } else {
                    $menu_request_uri_html = ' href="' . $menu_request_uri . '"';
                    $data_object_html      = '';
                    $object_type           = FALSE;
                    $object_id             = FALSE;
                }
                /** item_class */
                $in_page_class = '';
                if (is_taxonomy()) {
                    $current_uri       = get_current_uri();
                    $data_current_uri  = get_uri_data("uri=$current_uri");
                    $current_object_id = $data_current_uri->object_id;
                    if ($object_type == 'taxonomy' AND $object_id == $current_object_id) {
                        $in_page_class = ' ' . $current_page_class;
                    }
                }
                $add_has_sub_class = '';
                if ($this->has_sub_menu($id)) {
                    $add_has_sub_class = ' ' . $has_sub_class;
                }
                if ($menu_class != '') {
                    $item_class_html = ' class="' . $item_class . $menu_class . $in_page_class . $add_has_sub_class . ' hm-menu-' . $id . '"';
                } else {
                    $item_class_html = ' class="' . $item_class . $in_page_class . $add_has_sub_class . ' hm-menu-' . $id . '"';
                }
                /** item_id */
                if ($item_id) {
                    $item_id_html = ' id="' . $item_id . '"';
                } else {
                    $item_id_html = ' id="hm-menu-' . $id . '"';
                }
                /** open item */
                $output_html .= '<' . $item . $item_class_html . $item_id_html . $data_object_html . '>' . "\n";
                /** permalink before */
                $output_html .= $permalink_before;
                /** permalink_class */
                if ($permalink_class) {
                    $permalink_class_html = ' class="' . $permalink_class . $menu_class . '"';
                } else {
                    $permalink_class_html = '';
                }
                /** permalink */
                if (is_numeric($menu_icon)) {
                    $output_html .= img($menu_icon);
                }
                $output_html .= '<a' . $menu_request_uri_html . $menu_attr . $permalink_class_html . '>' . $menu_name . '</a>' . "\n";
                /** permalink after */
                $output_html .= $permalink_after;
                /** sub menu */
                if ($this->has_sub_menu($id)) {
                    $args['parent']        = $id;
                    $args['echo']          = FALSE;
                    $args['wrapper_class'] = $sub_menu_class . ' hm-sub-menu hm-sub-menu-of-' . $id . '';
                    $args['wrapper_id']    = '';
                    $output_html .= $this->get_menu($args);
                }
                /** close item */
                $output_html .= '</' . $item . '>' . "\n";
            }
        }
        /** close wrapper */
        $output_html .= '</' . $wrapper . '>' . "\n";
        if ($echo == TRUE) {
            echo $output_html;
        } else {
            return $output_html;
        }
    }
    public function has_sub_menu($menu_item_id) {
        $tableName  = DB_PREFIX . "object";
        $whereArray = array(
            'parent' => MySQL::SQLValue($menu_item_id, MySQL::SQLVALUE_NUMBER),
            'key' => MySQL::SQLValue('menu_item')
        );
        $this->SelectRows($tableName, $whereArray);
        if ($this->HasRecords()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
?>
