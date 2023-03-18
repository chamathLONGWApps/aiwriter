<?php

namespace App\Models;

use CodeIgniter\Model;

class FilePromptsModel extends Model{
    protected $table      = 'file_prompts';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = ['prompt', 'result', 'status'];

    // Dates
    // protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
}