<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Tabledx extends CI_Model{
    protected $ci_v = '4';
    protected $tb = null;
    protected $key = 'id';
    protected $rn = [];
    public $create_time = 'created_at';
    public $update_time = 'updated_at';
    public $delete_time = 'delete_set';
    protected $cp = [];
    protected $newcol = [];
    protected $builder = null;
    protected $condition = [];
    public $postget = [];
    protected $userid = null;
    protected $keys = "id";
    protected $allTable = [];
    protected $leftjoin = "";
    protected $joinparams = [];
    protected $primarytable = [];
    protected $selectparams = " * ";
    public $debugsql = "";
    protected $unsave = false;
    protected $debuger = false;
    protected $lmt = "";

    public function user()
    {
        $this->userid = user()->id;
        return $this->userid;
    }

    public function condition($arr = []){
        $this->condition = $arr;
    }

    public function table($var = NULL)
    {
        $this->tb = $var;
    }

    public function addColumn($name = "", $var = [])
    {
        $this->newcol[$name] = $var;
    }

    public function setkey($var = NULL)
    {
        $this->key = $var;
    }

    // get data
    public function getData($el = NULL)
    {
        return $this->db->query("SELECT * FROM $this->tb ")->result();
    }

    public function get($el)
    {
        return $this->db->query($el)->result();
    }

    public function limit($start = 0, $length = 10)
    {
      $this->lmt = " LIMIT  $start, $length ";
    }

    public function getResult()
    {

        if(count($this->condition) > 0){
            $cond = array_keys($this->condition);

            $condconf = " WHERE ";
            foreach($cond as $eml => $cop){
                $dat = $this->condition[$cop];

                if (is_array($dat)) {
                  if($eml == 0){
                    $opsi = $dat['opsi'];
                    $val = $dat['val'];
                    $condconf .= " $cop $opsi \"$val\" ";
                  }else{
                    $opsi = $dat['opsi'];
                    $val = $dat['val'];
                    $condconf .= " AND $cop $opsi \"$val\" ";
                  }
                }else{
                  if($eml == 0){
                    $condconf .= " $cop = \"$dat\" ";
                  }else{
                    $condconf .= " AND $cop = \"$dat\" ";
                  }
                }
            }
            $this->debugsql = "SELECT $this->selectparams FROM $this->tb $this->leftjoin $condconf AND $this->tb.$this->delete_time = '0' $this->lmt ";
            if ($this->debuger == true) {
              echo "<pre>";
              echo $this->debugsql;
              echo "</pre>";
            }
            return $this->db->query("SELECT $this->selectparams FROM $this->tb $this->leftjoin $condconf AND $this->tb.$this->delete_time = '0' $this->lmt ")->result();
        }else{
            $this->debugsql = "SELECT $this->selectparams FROM $this->tb $this->leftjoin WHERE $this->tb.$this->delete_time = '0' $this->lmt ";
            if ($this->debuger == true) {
              echo "<pre>";
              echo $this->debugsql;
              echo "</pre>";
            }
            return $this->db->query("SELECT $this->selectparams FROM $this->tb $this->leftjoin WHERE $this->tb.$this->delete_time = '0' $this->lmt ")->result();
        }
    }


    public function debug()
    {
      $this->debuger = true;
    }

    public function num_rows()
    {

      if(count($this->condition) > 0){
          $cond = array_keys($this->condition);

          $condconf = " WHERE ";
          foreach($cond as $eml => $cop){
              $dat = $this->condition[$cop];

              if (is_array($dat)) {
                if($eml == 0){
                  $opsi = $dat['opsi'];
                  $val = $dat['val'];
                  $condconf .= " $cop $opsi \"$val\" ";
                }else{
                  $opsi = $dat['opsi'];
                  $val = $dat['val'];
                  $condconf .= " AND $cop $opsi \"$val\" ";
                }
              }else{
                if($eml == 0){
                  $condconf .= " $cop = \"$dat\" ";
                }else{
                  $condconf .= " AND $cop = \"$dat\" ";
                }
              }
          }
          $this->debugsql = "SELECT $this->selectparams FROM $this->tb $this->leftjoin $condconf AND $this->tb.$this->delete_time = '0' ";

          if ($this->debuger == true) {
            echo "<pre>";
            echo $this->debugsql;
            echo "</pre>";
          }

          return $this->db->query("SELECT $this->selectparams FROM $this->tb $this->leftjoin $condconf AND $this->tb.$this->delete_time = '0' ")->num_rows();
      }else{
          $this->debugsql = "SELECT $this->selectparams FROM $this->tb $this->leftjoin WHERE $this->tb.$this->delete_time = '0' ";
          if ($this->debuger == true) {
            echo "<pre>";
            echo $this->debugsql;
            echo "</pre>";
          }
          return $this->db->query("SELECT $this->selectparams FROM $this->tb $this->leftjoin WHERE $this->tb.$this->delete_time = '0' ")->num_rows();
      }
    }

    public function getResultAll()
    {

        if(count($this->condition) > 0){
            $cond = array_keys($this->condition);

            $condconf = " WHERE ";

            foreach($cond as $eml => $cop){
                $dat = $this->condition[$cop];
                if($eml == 0){
                    $condconf .= " $cop = \"$dat\" ";
                }else{
                    $condconf .= " AND $cop = \"$dat\" ";
                }
            }
            $this->debugsql = "SELECT $this->selectparams FROM $this->tb $this->leftjoin $condconf";
            return $this->db->query("SELECT $this->selectparams FROM $this->tb $this->leftjoin $condconf")->result();
        }else{
            $this->debugsql = "SELECT $this->selectparams FROM $this->tb $this->leftjoin";
            return $this->db->query("SELECT $this->selectparams FROM $this->tb $this->leftjoin")->result();
        }
    }

    public function columnName($var = [])
    {
        $this->rn = $var;
    }

    public function custome($var = [])
    {
        $this->cp = $var;
    }

    // new table schema
    public function tablecreate($var = "")
    {
        /**
         *
         * $this rn = [
         *      'row' => 'index name'
         * ]
         *
         *
         * disable name width param string rn-no inn index name
         *
         */
        $name = array_keys($this->rn);
        $table = "<table $var >";
        $table .= "<thead>";
        $table .= "<tr>";
        foreach($name as $cc){
            if ($this->rn[$cc] != "rn-no") {
                $table .= "<th>";
                $table .= $this->rn[$cc];
                $table .= "</th>";
            }
        }
        if (count($this->newcol) > 0) {
            foreach(array_keys($this->newcol) as $mmq){
                $table .= "<th>";
                $table .= $mmq;
                $table .= "</th>";
            }
        }
        $table .= "</tr>";
        $table .= "</thead>";
        $table .= "<tbody>";
        $data = $this->getResult();
        foreach($data as $key => $elm){
            $table .= "<tr>";
            foreach($name as $cc){
                if ($this->rn[$cc] != "rn-no") {
                    if (isset($this->cp['data'])) {
                        if (isset($this->cp['data'][$cc])) {
                            $cmp = $this->cp['data'][$cc];
                            foreach($this->cp['key'] as $cm){
                                $cmp = str_replace('{{baseurl}}', base_url() , $cmp);
                                $cmp = str_replace('{{siteurl}}', site_url() , $cmp);
                                $cmp = str_replace('{{'.$cm.'}}', $elm->$cm, $cmp);
                            }
                            $table .= "<td>";
                            $table .= $cmp;
                            $table .= "</td>";
                        }else{
                            $table .= "<td>";
                            $table .= $elm->$cc;
                            $table .= "</td>";
                        }
                    }else{
                        $table .= "<td>";
                        $table .= $elm->$cc;
                        $table .= "</td>";
                    }
                }
            }
            if (count($this->newcol) > 0) {
                foreach(array_keys($this->newcol) as $mmq){
                    $cmp = $this->newcol[$mmq];
                    foreach($this->cp['key'] as $cm){
                        $cmp = str_replace('{{baseurl}}', base_url() , $cmp);
                        $cmp = str_replace('{{siteurl}}', site_url() , $cmp);
                        $cmp = str_replace('{{'.$cm.'}}', $elm->$cm, $cmp);
                    }
                    $table .= "<td>";
                    $table .= $cmp;
                    $table .= "</td>";
                }
            }
            $table .= "</tr>";
        }
        $table .= "</tbody>";
        $table .= "</table>";
        return $table;
    }


    public function createOpsi( $val = NULL, $name = NULL, $opsionalSelected = NULL )
    {
        $table = "";
        foreach($this->getResult() as $key => $value){
            $cp = $value->$val;
            if ($opsionalSelected == $cp) {
                $table .= "<option selected value='$cp'>";
                $table .= $value->$name;
                $table .= "</option>";
            }else{
                $table .= "<option value='$cp'>";
                $table .= $value->$name;
                $table .= "</option>";
            }
        }
        return $table;
    }


    public function addTable($table = NULL, $params = NULL, $primarytable = NULL, $key = NULL)
    {
        // set table if not null
        if ($table != NULL) {
            if ($key != NULL) {
                $this->allTable[$table] = $key;
                if($params != NULL){
                    $this->joinparams[$table] = $params;
                }
                if($primarytable != NULL){
                    $this->primarytable[$table] = $primarytable;
                }
            }else{
                $this->allTable[$table] = $this->keys;
                if($params != NULL){
                    $this->joinparams[$table] = $params;
                }
                if($primarytable != NULL){
                    $this->primarytable[$table] = $primarytable;
                }
            }
        }
    }

    public function key($key = "id")
    {
        $this->keys = $key;
    }

    public function addCreated($date = 'Y-m-d h:i:s')
    {
        $this->postget[$this->create_time] = date($date);
    }

    public function addUpdated($date = 'Y-m-d h:i:s')
    {
        $this->postget[$this->update_time] = date($date);
    }

    public function add($name="", $val="")
    {
        if ($name != "" && $val != "") {
            $this->postget[$name] = $val;
        }
    }

    public function getInput($ff = null)
    {
        $data = $_POST;
        if ($ff != NULL) {
            $data = $ff;
        }
        if(isset($data["csrf_test_name"])){
            unset($data["csrf_test_name"]);
        }

        $getKeys = array_keys($data);

        foreach ($getKeys as $ev => $aw) {
            $pantern = "/_nbr4/i";
            if (preg_match($pantern, $aw)) {
              unset($data[$aw]);
            }
        }

        $this->postget = $data;
        return $data;
    }

    public function dataInput()
    {
        echo "<pre>";
        print_r($this->postget);
        echo "</pre>";
    }

    public function filePost($data, $path, $id = "", $table = "")
	{
		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}
		$gambar = "";
		if ($id != "" && $table != "") {
			$gambar = $this->row();
            $gambar = (object) $gambar;
		}

        $nms = $data;
		$data = $_FILES[$data];
		$ext = pathinfo($data['name'], PATHINFO_EXTENSION);
		if ($data['name'] != "") {
			if ($gambar != "") {
				if (isset($gambar)) {
					unlink($path.'/'.$gambar->$data);
				}
			}
			// cek if file exist
			$uniq = uniqid();
			if(file_exists($path.'/'.$uniq.'.'.$ext)){
				unlink($path.'/'.$uniq.'.'.$ext);
				move_uploaded_file($data['tmp_name'], $path.'/'.$uniq.'.'.$ext);
			}else{
				move_uploaded_file($data['tmp_name'], $path.'/'.$uniq.'.'.$ext);
			}
			$this->postget[$nms] = $uniq.'.'.$ext;
          }else{
			$this->postget[$nms] = $gambar->$data;
          }
	}

    public function newData($var = [])
    {
        $data = NULL;
        if (count($this->postget) > 0) {
            $data = $this->postget;
        }
        if (count($var) > 0) {
            $data = $var;
        }
        return $this->db->insert($this->tb, $data);
    }

    public function select($params = ' * ')
    {
      $this->selectparams = $params;
    }

    public function leftJoin()
    {
        $this->leftjoin = "";
        foreach($this->allTable as $key => $val){
            $setone = NULL;
            if (isset($this->primarytable[$key])) {
                $setone = $this->primarytable[$key].".".$val;
            }else{
                $setone = $this->tb.".".$this->joinparams[$key];
            }
            if (isset($this->primarytable[$key])) {
                $settwo = $key.".".$this->joinparams[$key];
            }else{
                $settwo = $key.".".$val;
            }
            $this->leftjoin .= " \n LEFT JOIN $key ON $setone = $settwo \n ";
        }
    }


    public function unsafe()
    {
        $this->unsave = true;
    }


    public function row($dataName = null, $err = null)
    {

        if(count($this->condition) > 0){
            $cond = array_keys($this->condition);
            $condconf = " WHERE ";
            foreach($cond as $eml => $cop){
                $dat = $this->condition[$cop];

                if (is_array($dat)) {
                  if($eml == 0){
                    $opsi = $dat['opsi'];
                    $val = $dat['val'];
                    $condconf .= " $cop $opsi \"$val\" ";
                  }else{
                    $opsi = $dat['opsi'];
                    $val = $dat['val'];
                    $condconf .= " AND $cop $opsi \"$val\" ";
                  }
                }else{
                  if($eml == 0){
                    $condconf .= " $cop = \"$dat\" ";
                  }else{
                    $condconf .= " AND $cop = \"$dat\" ";
                  }
                }
            }

            $savemoment = "AND $this->tb.$this->delete_time = '0'";

            if ($this->unsave != false) {
                $savemoment = "";
            }

            $elm = $this->db->query("SELECT $this->selectparams FROM $this->tb $this->leftjoin $condconf $savemoment $this->lmt")->row();
            if ($elm != null) {
                if ($dataName != null) {
                  if($elm->$dataName != NULL){
                    return $elm->$dataName;
                  }else{
                    return $err;
                  }
                }else{
                  return $elm;
                }
            }else{
                return $err;
            }
        }else{
            $savemoment = "WHERE $this->tb.$this->delete_time = '0'";
            if ($this->unsave != false) {
                $savemoment = "";
            }
            $elm = $this->db->query("SELECT $this->selectparams FROM $this->tb $this->leftjoin $savemoment")->row();
            if ($elm != null) {
              if ($dataName != null) {
                if($elm->$dataName != NULL){
                  return $elm->$dataName;
                }else{
                  return $err;
                }
              }else{
                return $elm;
              }
            }else{
                return $err;
            }
        }
    }

    public function getLast()
    {
        $elm = $this->db->query("SELECT $this->selectparams FROM $this->tb $this->leftjoin WHERE $this->tb.$this->delete_time = '0' ORDER BY $this->key DESC ")->result();
        if (count($elm) > 0) {
            return $elm[0];
        }else{
            return null;
        }
    }

    public function setToUpdate()
    {
        $this->condition[$this->keys] = $this->postget[$this->keys];
        unset($this->postget[$this->keys]);
    }

    public function updateData($var = ["set" => [], "condition" => []])
    {
        $set = NULL;

        if (isset($var['set'])) {
            $set = $var['set'];
        }

        if (count($this->postget) > 0) {
            $set = $this->postget;
        }
        $condition = NULL;
        if (isset($var['condition'])) {
            $condition = $var['condition'];
        }
        if (count($this->condition) > 0) {
            $condition = $this->condition;
        }
        foreach(array_keys($set) as $el){
            $this->db->set($el, $set[$el]);
        }
        foreach(array_keys($condition) as $el){
            $this->db->where($el, $condition[$el]);
        }
        return $this->db->update($this->tb);
    }

    public function softDelete()
    {
        $data = NULL;
        if (count($this->postget) > 0) {
            $data = $this->postget;
        }

        $this->postget = [];

        return $this->updateData([
            "set" => [
                $this->delete_time => date('Y-m-d h:i:s')
            ],
            "condition" => $data
        ]);
    }

    public function delData($condition = [])
    {
        if (count($this->condition) != 0) {
            $condition = $this->condition;
        }
        foreach(array_keys($condition) as $el){
            $this->db->where($el, $condition[$el]);
        }
        $this->db->delete($this->tb);
    }



    public function createExcel($fileName = 'data.xlsx')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $name = array_keys($this->rn);

        $headers = [];

        foreach($name as $cc){
            if ($this->rn[$cc] != "rn-no") {
                $headers[] = $this->rn[$cc];
            }
        }
        if (count($this->newcol) > 0) {
            foreach(array_keys($this->newcol) as $mmq){
                $headers[] = $mmq;
            }
        }

        for ($i = 0, $l = sizeof($headers); $i < $l; $i++) {
            $sheet->setCellValueByColumnAndRow($i + 1, 1, $headers[$i]);
        }


        $datas = $this->getResult();
        $data = [];
        foreach($datas as $key => $elm){
            $kap = [];
            foreach($name as $cc){
                if ($this->rn[$cc] != "rn-no") {
                    if (isset($this->cp['data'])) {
                        if (isset($this->cp['data'][$cc])) {
                            $cmp = $this->cp['data'][$cc];
                            foreach($this->cp['key'] as $cm){
                                $cmp = str_replace('{{baseurl}}', base_url() , $cmp);
                                $cmp = str_replace('{{siteurl}}', site_url() , $cmp);
                                $cmp = str_replace('{{'.$cm.'}}', $elm->$cm, $cmp);
                            }
                             $kap[] = $cmp;
                        }else{
                            $kap[] = $elm->$cc;
                        }
                    }else{
                        $kap[] = $elm->$cc;
                    }
                }
            }
            if (count($this->newcol) > 0) {
                foreach(array_keys($this->newcol) as $mmq){
                    $cmp = $this->newcol[$mmq];
                    foreach($this->cp['key'] as $cm){
                        $cmp = str_replace('{{baseurl}}', base_url() , $cmp);
                        $cmp = str_replace('{{siteurl}}', site_url() , $cmp);
                        $cmp = str_replace('{{'.$cm.'}}', $elm->$cm, $cmp);
                    }
                    $kap[] = $cmp;
                }
            }
            $data[] = $kap;
        }

        for ($i = 0, $l = sizeof($data); $i < $l; $i++) { // row $i
            $j = 0;
            foreach ($data[$i] as $k => $v) { // column $j
                $sheet->setCellValueByColumnAndRow($j + 1, ($i + 1 + 1), $v);
                $j++;
            }
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');

    }
}
