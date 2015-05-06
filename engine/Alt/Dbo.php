<?php defined('ALT_PATH') or die('No direct script access.');

class Alt_Dbo {
    // database use
    public $db;
    // database instance to use
    public $dbinstance;
    // primary key
    public $pkey;

    // table name in database
    public $table_name;
    // table fields
    public $table_fields = array();
    // table dynamic column
    public $table_dyncolumn = NULL;
    // table dynamic columns data
    public $table_dynfields = array();

    // view name in database
    public $view_name;
    // view fields
    public $view_fields = array();
    // view dynamic columns data
    public $view_dynfields = array();

    /**
     * Create instance of this class
     * @return Alt_Dbo
     */
    public static function instance() {
        $classname = get_called_class();
        return new $classname();
    }

    public function __construct(){
        $this->db = Alt_Db::instance($this->dbinstance);
    }

    public function field($dbo, $field){
        if(count($this->table_dynfields) > 0){
            $regex = '/field\(([a-zA-z.\*]*)\)/i';
            preg_match_all($regex, $field, $match, PREG_PATTERN_ORDER);
            if(count($match) > 0) foreach($match[1] as $i => $item){
                $field = str_replace($match[0][$i], $dbo->field($item), $field);
            }
        }

        return $field;
    }


    public function get_select($data){
        $select = array();
        if(count($data) > 0){
            $total = count($data);
            for($i=0; $i<$total; $i++){

            }
        }
        return implode(', ', $select);
    }

    public function get_where($data){
        $where = array();
        if($data['where'] != null && $data['where'] != ''){
            $data['where'] = $this->field($data['where']);
            $where[] = $data['where'];
        }

        $fields = $dbo->get_fields();
        $dynfields = $dbo->get_dyncolumn() ? $dbo->get_dynfields() : array();

        foreach($data as $key => $value){
            if($fields[$key] !== null && $value != '') $dbo->where($dbo->field($key) . " like " . $dbo->quote("%" . $value . "%"));
            if($dynfields[$key] !== null && $value != ''){
                $tmp = $this->filter($key, $value);
                foreach($tmp as $k=>$v) {
                    $dbo->where($dbo->field($k) . " like " . $dbo->quote("%" . $v . "%"));
                }
            }
        }

        if($fields['isdeleted'] !== null && ($data['isdeleted'] == null || $data['isdeleted'] == '')){
            $dbo->where('isdeleted = 0');
        }
    }

    /**
     * get the group clause
     * @return string SQL group clause
     */
    protected function get_group() {
        if (!empty($this->groups)) {
            return " GROUP BY ".implode(",",$this->groups);
        }
        return "";
    }

    /**
     * get the order clause
     * @return string SQL order clause
     */
    protected function get_order() {
        if (!empty($this->orders)) {
            return " ORDER BY ".implode(",",$this->orders);
        }
        return "";
    }

    /**
     * get the limit clause
     * @return string SQL limit clause
     */
    protected function get_limit($data = array()) {
        if ($data['limit'] != "") {
            return " LIMIT " . $data['limit'] . " OFFSET " . $data['offset'];
        }
        return "";
    }

    public static function count($data = array()){
        // sql query
        $dbo = self::instance();
        $sql = "select count(*) as numofrow from " . $dbo->table_name . $dbo->get_where($data);
        $res = $dbo->db->query($sql);
        return !empty($res) ? $res[0]->numofrow : 0;
    }

    public static function retrieve($data = array()){

    }

    public static function get($data = array()){
        // returning data
        $dbo = self::instance();
        $data = $dbo->db->query('select ' . $dbo->get_select($data) . ' from ' . ($dbo->view_name ?: $dbo->table_name) . $dbo->get_where($data));
        return $data;
    }

    public static function insert($data){

    }

    public static function insert_multi($data){

    }

    public static function update($data){

    }

    public static function update_multi($data, $criteria){

    }


    public static function remove($data){

    }

    public static function remove_multi($data){

    }
}