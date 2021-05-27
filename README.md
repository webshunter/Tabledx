# Tabledx
Tabledx adalah class untuk mempermudah dalam pengelolaan database dan pembuatan datatable 

A. penggunaan di codeigniter 4

1. Letakkan Tabledx pada folder modal
2. Panggil Tabledx pada controller dimana Tabledx akan digunakan

```
<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Tabledx;

class Kuis extends BaseController
{
  ...
}

```

# Basic Penggunaan
mendapatkan semua data table dengan memanggil table seperti dibawah dan panggil function getResult() yang nantinya akan mereturn nilai berupa array object

```

public function index(){

  $tbl = new Tabledx;
  
  $tbl->tabel('nama_table');
  
  $tbl->getResult();

}

```

# Pengkondisian ( condition )
menambahkan kondisi pada table

```

public function index(){

  $tbl = new Tabledx;
  
  $tbl->tabel('nama_table');
  
  $tbl->condition([
    "id" => 2
  ])
  
  $tbl->getResult();

}

```
pengkondition dibuat dengan menggunakan array multidimention dengan keys sebagai rowname dari table dan value sebagai nilai dari rowname !

# mendapatkan single data
untuk mendapatkan singgle data anda dapt menggunakan function row() seperti dibawah dan menambahkan condition sesuai dengan kebutuhan, output dari function row() berupa object

```

public function index(){

  $tbl = new Tabledx;
  
  $tbl->tabel('nama_table');
  
  $tbl->condition([
    "id" => 2
  ])
  
  $tbl->row();

}

```

# Lef Join
untuk melakukan left join anda harus menambahkan table terlebih dahulu
