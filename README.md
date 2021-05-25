# Tabledx
Tabledx adalah class untuk mempermudah dalam pengelolaan database dan pembuatan datatable
penggunaan di codeigniter 4

1. letakkan Tabledx pada folder modal
2. panggil Tabledx pada controller dimana Tabledx akan digunakan

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

#basic penggunaan
mendapatkan semua data table

```

puclic function index(){

  $tbl = new Tabledx;
  
  $tbl->tabel('nama_table');
  
  $tbl->getResult();

}

```
