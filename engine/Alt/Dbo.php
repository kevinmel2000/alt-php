<?php defined('ALT_PATH') or die('No direct script access.');

class Alt_Dbo {
    // database instance for this class
    public $db;
    // database instance to use
    public $db_instance;
    // autoincrement flag
    public $autoinc = true;
    // primary key for the table
    public $pkey;
    // table name in database
    protected $table_name;
    // table fields
    protected $table_fields = array();
    // table dynamic column name
    protected $table_dyncolumn;
    // table dynamic fields data
    protected $table_dynfields = array();
    // view name in database
    protected $view_name;
    // view fields
    protected $view_fields = array();
    // view dynamic column name
    protected $view_dyncolumn;
    // view dynamic fields data
    protected $view_dynfields = array();

    /**
     * Constructing class
     * @return void
     */
    public function __construct() {
        $this->table_name   = $this->table_name ?: get_class($this);
        $this->pkey         = $this->pkey ?: $this->table_name ."id";
        $this->db           = $this->db ?: Alt_Db::instance($this->db_instance);
    }

    /**
     * Create instance of this class
     * @return Alt_Dbo
     */
    public static function instance() {
        $classname = get_called_class();
        return new $classname();
    }

    /**
     * @param  $instance_name
     * @return Alt_Dbo
     */
    public function reinstance($instance_name) {
        $this->db = Alt_Db::instance($instance_name);
        return $this;
    }

    /**
     * Creating column_create query dynamic column
     * @param $field
     * @param $value
     * @return array
     */
    public function column_create($field, $value){
        $field = $this->quote($field);

        switch(gettype($value)){
            case "array":
            case "object":
                $dyncol = array();
                foreach($value as $key => $val){
                    list($key, $val) = $this->column_create($key, $val);
                    $dyncol[] = $key;
                    $dyncol[] = $val;
                }
                $value = count($dyncol) > 0 ? "COLUMN_CREATE (".implode(",",$dyncol).")" : "''";
                break;
            default:
                $value = $this->quote($value);
                break;
        }
        return array($field, $value);
    }

    /**
     * Creating column_get query for dynamic column
     * @param $column
     * @param array $array
     * @return string
     */
    public function column_get($column, $array = array()){
        $str = "COLUMN_GET(";
        if(count($array) == 0) {
            $str .= $this->get_dyncolumn() . ", " . $this->quote($column) . ' AS CHAR';
        }else{
            $str .= $this->column_get($column, array_slice($array, 0, count($array)-1)) . ", " . $this->quote($array[count($array)-1]) . ' AS CHAR';
        }
        $str .= ")";
        return $str;
    }

    /**
     * Support dynamic field selection using dot
     * @param $field
     * @return string
     */
    public function field($field){
        $column = explode(".", $field);
        $str = "";

        $dyncolumn = $this->get_dyncolumn();
        $dynfields = $this->get_dynfields();

        if($dyncolumn != null && array_key_exists($column[0], $dynfields)){
            $tmpcolumn = count($column) == 0 ? array() : array_slice($column, 1);
            $isall = $tmpcolumn[count($tmpcolumn)-1] == '*';
            $tmpcolumn = $isall ? array_slice($tmpcolumn, 0, count($tmpcolumn)-1) : $tmpcolumn;
            $format = $this->column_get($column[0], $tmpcolumn);
            if($isall) $format = "CAST(COLUMN_JSON(" . $format . ") AS CHAR)";
            $str =  $format;
        }else{
            $str = $field;
        }
        return $str;
    }

    /**
     * Support dynamic field selection using dot in any string, e.g. select field(x.y)
     * @param $field
     * @return mixed
     */
    public function fieldstring($field){
        if($this->get_dyncolumn()){
            $regex = '/field\(([a-zA-z.\*]*)\)/i';
            preg_match_all($regex, $field, $match, PREG_PATTERN_ORDER);
            if(count($match) > 0) foreach($match[1] as $i => $item){
                $field = str_replace($match[0][$i], $this->field($item), $field);
            }
        }

        return $field;
    }

    /**
     * Support array filter, reformat to ".", e.g. post data from client x[y] will be formatted to x.y;
     * @param $key
     * @param $value
     * @param string $prev
     * @return array
     */
    public function filter($key, $value, $prev = ""){
        $res = array();
        if(is_array($value)) {
            foreach($value as $k => $v){
                $res = array_merge($res, $this->filter($k, $v, ($prev != "" ? $prev . "." : "") . $key));
            }
        }else{
            $res[($prev != "" ? $prev . "." : "") . $key] = $value;
        }
        return $res;
    }

    /**
     * Get tablename
     * @param bool $returnview
     */
    public function get_tablename($returnview = true){
        return $returnview && isset($this->view_name) ? $this->view_name : $this->table_name;
    }

    /**
     * Get table field
     * @param bool $returnview
     * @return array
     */
    public function get_fields($returnview = true){
        return $returnview && isset($this->view_fields) ? $this->view_fields : $this->table_fields;
    }

    /**
     * Get dynamic column name
     * @param bool $returnview
     * @return mixed
     */
    public function get_dyncolumn($returnview = true){
        return $returnview && isset($this->view_dyncolumn) ? $this->view_dyncolumn : $this->table_dyncolumn;
    }

    /**
     * Get dynamic fields
     * @param bool $returnview
     * @return mixed
     */
    public function get_dynfields($returnview = true){
        return $returnview && isset($this->view_dynfields) ? $this->view_dynfields : $this->table_dynfields;
    }

    /**
     * Get the where clause
     * @return string SQL group clause
     */
    public function get_select($data = array()){
        $select = array();

        if($data['select'] != null && $data['select'] != ''){
            $data['select'] = $this->fieldstring($data['select']);
            $select[] = $data['select'];
        }

        return count($select) > 0 ? implode(", ", $select) : "*";
    }

    /**
     * Get the where clause
     * @return string SQL group clause
     */
    public function get_where($data = array()){
        $where = array();

        if($data['where'] != null && $data['where'] != ''){
            $data['where'] = $this->field($data['where']);
            $where[] = $data['where'];
        }

        foreach($data as $key => $value){
            if($this->table_fields[$key] !== null && $value != ''){
                $where[] = $this->field($key) . " like " . $this->quote("%" . $value . "%");
            }else if($this->table_dynfields[$key] !== null && $value != ''){
                $tmp = $this->filter($key, $value);
                foreach($tmp as $k=>$v) {
                    $where[] = $this->field($k) . " like " . $this->quote("%" . $v . "%");
                }
            }
        }

        if($this->table_fields['isdeleted'] !== null && $this->view_fields['isdeleted'] !== null && ($data['isdeleted'] == null || $data['isdeleted'] == '')){
            $where[] = 'isdeleted = 0';
        }

        return count($where) > 0 ? " where " . implode(" and ", $where) : "";
    }

    /**
     * Get the group clause
     * @return string SQL group clause
     */
    public function get_group($data = array()) {
        if($data['group'] != null && $data['group'] != ''){
            return " GROUP BY " . $data['group'];
        }
        return "";
    }

    /**
     * Get the order clause
     * @return string SQL order clause
     */
    public function get_order($data = array()) {
        if($data['order'] != null && $data['order'] != ''){
            return " ORDER BY " . $data['order'];
        }
        return "";
    }

    /**
     * Get the limit clause
     * @return string SQL limit clause
     */
    public function get_limit($data = array()) {
        if($data['limit'] != null && $data['limit'] != ''){
            return " LIMIT " . $data['limit'] . " OFFSET " . ($data['offset'] ?: 0);
        }
        return "";
    }

    /**
     * Get the join clause
     * @return string SQL join clause
     */
    public function get_join($data = array()) {
        if($data['join'] != null && $data['join'] != ''){
            return $data['join'];
        }
        return "";
    }

    /**
     * Quote value
     * @param $string
     * @return mixed
     */
    public function quote($string){
        return $this->db->quote($string);
    }

    /**
     * count designated row
     * @param array $data
     * @param boolean $returnsql, is returning sql
     * @return int num of row
     */
    public function count($data = array(), $returnsql = false) {
        // sql query
        $sql = "select count(*) as numofrow from " . ($this->view_name ?: $this->table_name) . $this->get_where($data);
        if($returnsql) return $sql;

        $res = $this->db->query($sql);
        return !empty($res) ? $res[0]->numofrow : 0;
    }

    /**
     * insert into database
     * @param usedefault bool set true if you want to use default value for empty table_fields set by DBO
     * @return int inserted row
     */
    public function create($data, $returnsql = false) {
        // constructing sql
        $sql = "insert into " . $this->table_name . " (";

        // imploding field names
        if ($this->pkey != "" && $this->autoinc)
            unset($data[$this->pkey]);

        // set field values
        $fields = $this->get_fields(false);
        $fnames = array();
        $values = array();
        foreach ($data as $field => $value) if(isset($fields[$field])) {
            $fnames[] = $field;
            $values[] = $this->quote($value);
        }

        // dynamic columns
        $dyncolumn = $this->get_dyncolumn(false);
        $dynfields = $this->get_dynfields(false);
        if ($dyncolumn != null && count($dynfields) > 0) {
            $fnames[] = $dyncolumn;
            $dyncol = array();
            foreach ($dynfields as $field => $value) {
                list($field, $value) = $this->column_create($field, $value, 'COLUMN_CREATE');
                $dyncol[] = $field;
                $dyncol[] = $value;
            }
            $values[] = "COLUMN_CREATE(".implode(",",$dyncol).")";
        }
        // forge sql
        $sql .= implode(",",$fnames) .") values (". implode(",",$values) .")";
        if($returnsql) return $sql;

        // execute or return query
        $res = $this->db->query($sql);
        return $res;
    }

    /**
     * Gets data from database
     * @return array of data
     */
    public function retrieve($data = array(), $returnsql = false) {
        $sql = "SELECT ".$this->get_select($data)." FROM ".$this->get_tablename() . $this->get_where($data).$this->get_group($data).$this->get_order($data).$this->get_join($data).$this->get_limit($data);
        if($returnsql) return $sql;

        // returning data
        $data = $this->db->query($sql, "array");
        if($this->table_dyncolumn) {
            for ($i = 0; $i < count($data); $i++) {
                unset($data[$i]->{$this->table_dyncolumn});
                foreach ($data[$i] as $key => $value) {
                    $decoded = json_decode($value);
                    $data[$i]->$key = $decoded !== NULL && (gettype($decoded) == 'array' || gettype($decoded) == 'object') ? json_decode($value) : $value;
                }
            }
        }
        return $data;
    }

    /**
     * update the data
     * @return int affected row
     */
    public function update($data, $returnsql = false) {
        // constructing sql
        $sql = "update $this->table_name set ";

        // imploding field names
        $updfield = $this->table_fields;
        unset($updfield[$this->pkey]);
        // set field values
        $fields = array();
        foreach ($updfield as $field => $value) {
            //if ($value != null)
            if ($this->mark_update[$field])
                $fields[] = $field." = ".$this->quote($value);
        }
        // dynamic columns
        if ($this->table_dyncolumn != null && count($this->dynfields) > 0) {
            $dyncol = array();
            foreach ($this->dynfields as $field => $value) {
                if($this->mark_updatedyn[$field]) {
                    list($field, $value) = $this->column_create($field, $value, 'COLUMN_CREATE');
                    $dyncol[] = $field;
                    $dyncol[] = $value;
                }
            }
            if (count($dyncol) > 0)
                $fields[] = "$this->table_dyncolumn = COLUMN_CREATE(".implode(",",$dyncol).")";
        }
        // forge sql
        $sql .= implode(",",$fields) . ($use_where? $this->get_where() : " where $this->pkey = '". $this->table_fields[$this->pkey] ."'");

        // execute or return query
        if($returnsql){
            return $sql;
        }else{
            $res = $this->db->query($sql);
            return $res;
        }
    }

    /**
     * delete the data
     * @return int num of deleted data
     */
    public function delete($data) {
        if ($this->table_fields[$this->pkey] == "") {
            // no primary key set
            // we just use the wheres that set, but, if wheres not set, prevent it from deleting entire table
            if (empty($this->wheres)) return -1;
        }
        else {
            // delete just those key
            if (!$use_where) $this->where($this->pkey ." = '".$this->table_fields[$this->pkey]."'");
        }

        $res = $this->db->query("delete from $this->table_name ".$this->get_where());
        return $res;
    }
}