<?php

namespace App\Models;

use CodeIgniter\Model;

class FilePromptsModel extends Model{
    protected $table      = 'file_prompts';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = ['file_id', 'prompt', 'topic', 'result', 'status'];

    // Dates
    // protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';

    protected $files = 'files';

    public function saveArticle($fileId, $filePromptId, $article){
        $this->db->transBegin();

        $this->db->table($this->table)->set(['result' => $article, 'status' => 'completed'])->where(['id'=> $filePromptId])->update();

        $counts = $this->db->table($this->table)->where(['status' => 'pending', 'file_id' => $fileId])->countAllResults();
        log_message('debug', $counts);
        if ($counts == 0)
            $this->db->table($this->files)->set(['status' => 'completed'])->where(['id'=>$fileId])->update();
        
        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
        } else {
            $this->db->transCommit();
        } 
    }
}