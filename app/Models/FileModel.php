<?php

namespace App\Models;

use CodeIgniter\Model;

class FileModel extends Model{
    protected $table      = 'files';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = ['name'];

    // Dates
    // protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
}