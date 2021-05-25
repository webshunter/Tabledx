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
mendapatkan semua data table

```

public function index(){

  $tbl = new Tabledx;
  
  $tbl->tabel('nama_table');
  
  $tbl->getResult();

}

```

# Condition
menambahkan kondisi pada table
1. cara 1

```

public function index(){

  $tbl = new Tabledx;
  
  $tbl->tabel('nama_table');
  
  $tbl->getResult();

}

```

2. cara 2

```

public function index(){

  $tbl = new Tabledx;
  
  $tbl->tabel('nama_table');
  
  $tbl->getResult();

}

```
