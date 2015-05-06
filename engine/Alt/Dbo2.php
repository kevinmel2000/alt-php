<?php defined('ALT_PATH') or die('No direct script access.');

class Alt_Dbo2 {
    // table alias on select
    protected $alias;
    // auto-increment on primary key
    protected $autoinc = true;
    // database instance for this class
    protected $db;
    // database instance to use
    public $db_instance;
    // dynamic column name
    protected $dyncolumn = null;
    // dynamic columns data
    protected $dynfields = array();
    // table fields
    protected $fields = array();
    // primary key for the table
    protected $pkey;
    // table name in database
    protected $tablename;
    // where clauses
    protected $wheres = array();
    // group by clauses
    protected $groups = array();
    // order clauses
    protected $orders = array();
    // limit clauses
    protected $limit;
    // offset clauses
    protected $offset;
    // this class belongs to what class
    protected $belongs_to = array();
    // this class has many to
    protected $has_manys = array();
    // joined entity on select
    protected $joins = array();
    // return type for query, default is object (object / array)
    public $rtype = "object";
    // field update marker
    protected $mark_update = array();
    // dynfield update marker
    protected $mark_updatedyn = array();
    // select marker, for specific select
    protected $select_fields = array();

    /**
     * Constructing class
     * @return void
     */
    public function __construct() {
        $this->tablename = get_class($this);
        $this->pkey = $this->tablename ."id";
        $this->db = Alt_Db::instance($this->db_instance);
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
     * Define getter for table fields
     * @return mixed table field value
     */
    public function __get($key) {
        if (array_key_exists($key,$this->fields)) {
            return $this->fields[$key];
        }
        else if ($this->dyncolumn != null && array_key_exists($key,$this->dynfields)) {
            return $this->dynfields[$key];
        }

        return null;
    }

    /**
     * Define setter for table fields
     * @return void
     */
    public function __set($key,$value) {
        if (array_key_exists($key,$this->fields)) {
            // check marker, is it exists
            if (!is_array($this->mark_update))
                $this->mark_update = array_fill_keys($this->fields,0);
            $this->fields[$key] = $value;
            $this->mark_update[$key] = 1;
        }
        else {
            // add/update dynamic columns
            $this->dynfields[$key] = $value;
            $this->mark_updatedyn[$key] = 1;
        }
    }

    /**
     * count designated row
     * @return int num of row
     */
    public function count() {
        // sql query
        $sql = "select count(*) as numofrow from $this->tablename" . $this->get_where();
        $res = $this->db->query($sql);
        if (!empty($res))
            return $res[0]->numofrow;
        else
            return 0;
    }

    public function column_create($field, $value, $type = 'COLUMN_CREATE'){
        $field = $this->db->quote($field);

        switch(gettype($value)){
            case "array":
            case "object":
                $dyncol = array();
                foreach($value as $key => $val){
                    list($key, $val) = $this->column_create($key, $val, $type);
                    $dyncol[] = $key;
                    $dyncol[] = $val;
                }
                $value = count($dyncol) > 0 ? $type . "(".implode(",",$dyncol).")" : "''";
                break;
            default:
                $value = $this->db->quote($value);
                break;
        }
        return array($field, $value);
    }

    /**
     * insert into database
     * @param usedefault bool set true if you want to use default value for empty fields set by DBO
     * @return int inserted row
     */
    public function insert($usedefault = false, $issql = false) {
        // constructing sql
        $sql = "insert into $this->tablename (";
        // imploding field names
        $insfield = $this->fields;
        if ($this->pkey != "" && $this->autoinc)
            unset($insfield[$this->pkey]);
        // set field values
        $fnames = array();
        $values = array();
        foreach ($insfield as $field => $value) {
            if (($value == null && $usedefault) || $value != null) {
                $fnames[] = $field;
                $values[] = $this->db->quote($value);
            }
        }
        // dynamic columns
        if ($this->dyncolumn != null && count($this->dynfields) > 0) {
            $fnames[] = $this->dyncolumn;
            $dyncol = array();
            foreach ($this->dynfields as $field => $value) {
                list($field, $value) = $this->column_create($field, $value, 'COLUMN_CREATE');
                $dyncol[] = $field;
                $dyncol[] = $value;
            }
            $values[] = "COLUMN_CREATE(".implode(",",$dyncol).")";
        }
        // forge sql
        $sql .= implode(",",$fnames) .") values (". implode(",",$values) .")";

        // execute or return query
        //print_r($sql);die;
        if($issql){
            return $sql;
        }else{
            $res = $this->db->query($sql);
            return $res;
        }
    }

    /**
     * update the data
     * @return int affected row
     */
    public function update($use_where = false, $issql = false) {
        // constructing sql
        $sql = "update $this->tablename set ";
        // imploding field names
        $updfield = $this->fields;
        unset($updfield[$this->pkey]);
        // set field values
        $fields = array();
        foreach ($updfield as $field => $value) {
            //if ($value != null)
            if ($this->mark_update[$field])
                $fields[] = $field." = ".$this->db->quote($value);
        }
        // dynamic columns
        if ($this->dyncolumn != null && count($this->dynfields) > 0) {
            $dyncol = array();
            foreach ($this->dynfields as $field => $value) {
                if($this->mark_updatedyn[$field]) {
                    list($field, $value) = $this->column_create($field, $value, 'COLUMN_CREATE');
                    $dyncol[] = $field;
                    $dyncol[] = $value;
                }
            }
            if (count($dyncol) > 0)
                $fields[] = "$this->dyncolumn = COLUMN_CREATE(".implode(",",$dyncol).")";
        }
        // forge sql
        $sql .= implode(",",$fields) . ($use_where? $this->get_where() : " where $this->pkey = '". $this->fields[$this->pkey] ."'");

        // execute or return query
        if($issql){
            return $sql;
        }else{
            $res = $this->db->query($sql);
            return $res;
        }

    }

    /**
     * update the data - non primary key based
     * @return int affected row
     */
    public function update_multi() {
        return $this->update(true);
    }

    /**
     * delete the data
     * @return int num of deleted data
     */
    public function delete($use_where = false) {
        if ($this->fields[$this->pkey] == "") {
            // no primary key set
            // we just use the wheres that set, but, if wheres not set, prevent it from deleting entire table
            if (empty($this->wheres)) return -1;
        }
        else {
            // delete just those key
            if (!$use_where) $this->where($this->pkey ." = '".$this->fields[$this->pkey]."'");
        }

        $res = $this->db->query("delete from $this->tablename ".$this->get_where());
        return $res;
    }

    /**
     * delete multiple data
     * @return int num of deleted data
     */
    public function delete_multi() {
        return $this->delete(true);
    }


    /**
     * Gets all data from database
     * @return array of data
     */
    public function get($rtype = "object") {
        // returning data
        $data = $this->db->query($this->get_sql(),$rtype);
        if($this->dyncolumn) {
            for ($i = 0; $i < count($data); $i++) {
                unset($data[$i]->{$this->dyncolumn});
                foreach ($data[$i] as $key => $value) {
                    $decoded = json_decode($value);
                    $data[$i]->$key = $decoded !== NULL && (gettype($decoded) == 'array' || gettype($decoded) == 'object') ? json_decode($value) : $value;
                }
            }
        }
        return $data;
    }

    /**
     * retrieve data by id, or by where clause - limited 1
     * @return int retrived row data
     */
    public function retrieve($use_where = false) {
        // set clause
        if (!$use_where)
            $this->where("$this->pkey = '".$this->fields[$this->pkey]."'");
        // else, just use the where, make sure only retrieve one row
        else
            $this->limit(1);
        // query data
        $result = $this->db->query($this->get_sql(),"array");
        // check the result
        if (count($result) > 0) {
            foreach ($result[0] as $key => $value) {
                if (array_key_exists($key,$this->fields))
                    $this->fields[$key] = $value;
                else if ($this->dyncolumn != null)
                    $this->dynfields[$key] = json_decode($value) !== null ? json_decode($value) : $value;
            }
            return count($result);
        }
        else {
            return 0;
        }
    }

    /**
     * Get the sql for querying database, usually to supply dbgridview
     * @return  string SQL Query
     */
    public function get_sql() {
        $fields = array_keys($this->fields);
        foreach($this->dynfields as $field => $defaultvalue){
            $fields[] = $this->field($field . (gettype($defaultvalue) == "string" ? "" : ".*")) . " as " . $field;
        }
        $fields = implode(",", $fields);

        $selects = count($this->select_fields) > 0? implode(",",array_keys($this->select_fields)) : $fields;
        // returning sql
        return "SELECT ".$selects." FROM ".$this->tablename. $this->get_where().$this->get_group().$this->get_order().$this->get_limit();
    }

    /**
     * get the where clause
     * @return string SQL where clause
     */
    protected function get_where() {
        // constructing where clause
        $first = true;
        $where = "";
        if (count($this->wheres) > 0) {
            $where = " WHERE ";
            foreach ($this->wheres as $w) {
                $where .= ($first? "" : " AND ").$w;
                $first = false;
            }
        }
        return $where;
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
    protected function get_limit() {
        if ($this->limit != "") {
            return " LIMIT $this->limit OFFSET $this->offset";
        }
        return "";
    }

    public function column_get($column, $array = array()){
        $str = "COLUMN_GET(";
        if(count($array) == 0) {
            $str .= $this->dyncolumn . ", " . $this->quote($column) . ' AS CHAR';
        }else{
            $str .= $this->column_get($column, array_slice($array, 0, count($array)-1)) . ", " . $this->quote($array[count($array)-1]) . ' AS CHAR';
        }
        $str .= ")";
        return $str;
    }

    public function field($field){
        $column = explode(".", $field);
        $str = "";
        if($this->dyncolumn != "" && array_key_exists($column[0], $this->dynfields)){
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
     * set specific select fields
     * @return Alt_Dbo
     */
    public function select() {
        $params = func_get_args();
        if (count($params) > 0) {
            $fields = array();
            foreach ($params as $col) {
                $col = trim($col);
                if (array_key_exists($col,$this->fields)) {
                    $fields[] = $col;
                }
                else if ($this->dyncolumn != "") {
                    // dynamic column enabled
                    $select = explode(" ", $col);
                    $f = $select[0];
                    $as = count($select) > 1 ? " " . implode(" ", array_slice($select, 1)) : "";
                    $fields[] = $this->field($f) . $as;
                }else{
                    $fields[] = $col;
                }
            }
            if (count($fields) > 0)
                $this->select_fields = array_flip($fields);
        }
        return $this;
    }

    /**
     * Add where clause
     * @param string a where clause
     * @return Alt_Dbo
     */
    public function where($where) {
        $this->wheres[] = $where;
        return $this;
    }

    /**
     * Add group clause
     * @param string a group clause
     * @return Alt_Dbo
     */
    public function group_by($group) {
        $this->groups[] = $group;
        return $this;
    }

    /**
     * Add order clause
     * @param string an order clause
     * @return Alt_Dbo
     */
    public function order_by($order,$sort = "ASC") {
        $this->orders[] = $order." ".$sort;
        return $this;
    }

    /**
     * set the limit
     * @param limit int num of data to be obtained
     * @param offset int offset from first row
     * @return Alt_Dbo
     */
    public function limit($limit,$offset = 0) {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    /**
     * set this class reference belong to another class
     * @param string class name
     * @param string foreign key to designated class
     * @return void
     */
    protected function belong_to($classname,$fkeyname,$options = array()) {
        if (!isset($this->belongs_to[$classname])) {
            $this->belongs_to[$classname] = array("fkey" => $fkeyname,"options" => $options);
        }
    }

    /**
     * join to another entity
     * @param string class name
     * @return void
     */
    public function join($classname) {
        if (!in_array($classname,$this->joins))
            $this->joins[] = $classname;
    }

    /**
     * Reset all clause
     * @return Alt_Dbo
     */
    public function reset() {
        $this->wheres = array();
        $this->groups = array();
        $this->orders = array();
        $this->select_fields = array();
        $this->mark_update = array();
        $this->limit = "";
        $this->offset = "";
        return $this;
    }

    /**
     * @param  $input any inputed data
     * @return string quoted sql string
     */
    public function quote($input) {
        return $this->db->quote($input);
    }
}