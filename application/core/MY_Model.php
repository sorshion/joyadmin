<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    protected $table_name   = '';
    protected $primary_key  = 'id';

    private function _order()
    {
        return $this->primary_key . ' ASC';
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function query($sql)
    {
        return $this->db->query($sql);
    }

    public function isMultiArray($array)
    {
        foreach ($array as $v)
            if (is_array($v))
                return true;
        return false;
    }

    public function get($where = null, $is_array = true)
    {
        $method = $is_array ? 'result_array' : 'result';

        $this->where($where);

        return $this->db->get($this->table_name)->$method();
    }

    public function find($where = null, $is_array = true)
    {
        $method = $is_array ? 'row_array' : 'row';

        $this->where($where);

        return $this->db->get($this->table_name)->$method();
    }

    public function save($data, $where = null)
    {
        if (empty($data) || !is_array($data)) {
            return false;
        }
        if (is_numeric($where)) {
            $this->db->set($data);
            $this->db->where($this->primary_key, intval($where));
            $this->db->update($this->table_name);
        } else {
            $this->where($where);
            $this->db->update($this->table_name, $data);
        }

        return $this->db->affected_rows();
    }

    public function add($data)
    {
        if (empty($data) || !is_array($data)) {
            return false;
        }

        $this->db->insert($this->table_name, $data);

        return $this->db->insert_id();
    }

    public function addAll($data)
    {
        if ($this->isMultiArray($data)) {
            $this->db->insert_batch($this->table_name, $data);
        } else {
            $this->db->insert($this->table_name, $data);
        }

        return $this->db->affected_rows();
    }

    public function from($table_name)
    {
        $this->table_name = $table_name;

        return $this;
    }

    public function delete($where = null, $filed = null)
    {
        if (is_array($where)) {
            if (isset($filed)) {
                $this->where_in($where, $filed);
            } else {
                $this->where($where);
            }
        } elseif (is_numeric($where)) {
            $this->db->where($this->primary_key, intval($where));
        } else {
            $this->where($where);
        }
        $this->db->delete($this->table_name);
        return $this->db->affected_rows();
    }

    public function upPlus($field, $id)
    {
        $this->db->set($field, $field . '+1', false);
        if (is_array($id)) {
            $this->where($id);
        } elseif (is_numeric($id)) {
            $this->db->where($this->primary_key, intval($id));
        }
        $this->db->update($this->table_name);
        return $this->db->affected_rows();
    }

    public function down($field, $id)
    {
        $this->db->set($field, $field . '-1', false);
        if (is_array($id)) {
            $this->where($id);
        } elseif (is_numeric($id)) {
            $this->db->where($this->primary_key, intval($id));
        }
        $this->db->update($this->table_name);
        return $this->db->affected_rows();
    }

    public function where($where = null, $value = null)
    {
        if (isset($where)) {
            if (is_numeric($where) && is_null($value)) {
                $this->db->where($this->primary_key, intval($where));
            } else {
                $this->db->where($where, $value);
            }
        }

        return $this;
    }

    public function or_where($where = null, $value = null)
    {
        if (isset($where)) {
            if (is_numeric($where) && is_null($value)) {
                $this->db->where($this->primary_key, intval($where));
            } else {
                $this->db->where($where, $value);
            }
        }

        return $this;
    }

    // TP模式
    public function limit($offset = 1, $limit = '')
    {
        if (!$limit) {
            $this->db->limit($offset);
        } else {
            $this->db->limit($limit, $offset);
        }
        return $this;
    }


    public function select($fields = null)
    {
        if (isset($fields)) {
            $fields = (is_array($fields)) ? implode(',', $fields) : $fields;
            $this->db->select($fields);
        } else {
            $this->db->select('*');
        }
        return $this;
    }

    public function order_by($order_by = '', $rand = false)
    {
        if ($rand) {
            $this->db->order_by($order_by, 'RANDOM');
        } else {
            if (!$order_by) {
                if ($this->_order())
                    $this->db->order_by($this->_order());
            } else {
                $this->db->order_by($order_by);
            }
        }
        return $this;
    }

    public function where_in($fileds = '', $key = '')
    {
        if ($fileds) {
            if (!$key) {
                $this->db->where_in($this->primary_key, $fileds);
            } else {
                $this->db->where_in($key, $fileds);
            }
        }
        return $this;
    }

    public function like($value = '', $key = '', $match = 'both')
    {
        if ($value) {
            if (is_array($value) && !$key) {
                $this->db->like($value);
            } else {
                if (!$key) {
                    $this->db->like($this->primary_key, $value, $match);
                } else {
                    $this->db->like($key, $value, $match);
                }
            }
        }
        return $this;
    }

    public function group_by($group)
    {
        $this->db->group_by($group);
        return $this;
    }

    public function distinct($distinct)
    {
        $this->db->distinct($distinct);
        return $this;
    }

    public function count()
    {
        return $this->db->get($this->table_name)->num_rows();
    }

    public function max($filed, $as = '')
    {
        if ($as) {
            return $this->db->select_max($filed, $as)->get($this->table_name)->row();
        } else {
            return $this->db->select_max($filed)->get($this->table_name)->row();
        }
    }

    public function min($filed, $as = '', $object = true)
    {
        if ($as) {
            if ($object) {
                return $this->db->select_min($filed, $as)->get($this->table_name)->row();
            } else {
                return $this->db->select_min($filed, $as)->get($this->table_name)->row_array();
            }
        } else {
            if ($object) {
                return $this->db->select_min($filed)->get($this->table_name)->row();
            } else {
                return $this->db->select_min($filed)->get($this->table_name)->row_array();
            }
        }
    }

    public function avg($filed, $as = '', $object = true)
    {
        if ($as) {
            if ($object) {
                return $this->db->select_avg($filed, $as)->get($this->table_name)->row();
            } else {
                return $this->db->select_avg($filed, $as)->get($this->table_name)->row_array();
            }
        } else {
            if ($object) {
                return $this->db->select_avg($filed)->get($this->table_name)->row();
            } else {
                return $this->db->select_avg($filed)->get($this->table_name)->row_array();
            }
        }
    }

    public function sum($filed, $as = '', $object = true)
    {
        if ($as) {
            if ($object) {
                return $this->db->select_sum($filed, $as)->get($this->table_name)->row();
            } else {
                return $this->db->select_sum($filed, $as)->get($this->table_name)->row_array();
            }
        } else {
            if ($object) {
                return $this->db->select_sum($filed)->get($this->table_name)->row();
            } else {
                return $this->db->select_sum($filed)->get($this->table_name)->row_array();
            }
        }
    }
}
