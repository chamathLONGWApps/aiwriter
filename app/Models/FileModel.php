<?php

namespace App\Models;

use CodeIgniter\Model;

class FileModel extends Model
{
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

    protected $filePrompt = 'file_prompts';


    public function getNextFileToProcess() {
        return $this->db->table("{$this->table} f")
            ->select("f.id, fp.updated_at")
            ->join("{$this->filePrompt} fp", "f.id = fp.file_id", "INNER")
            ->where("(f.status = 'inprogress' OR f.status = 'pending')")
            ->where(['fp.status' => 'inprogress'])
            ->get()->getRow();
    }

    public function getFilePrompt($fpStatus)
    {
        $this->db->transBegin();

        $res = $this->db->table("{$this->table} f")
            ->select("f.id, f.name, f.status, fp.id fpId, fp.status fpStatus, fp.topic, fp.prompt")
            ->join("{$this->filePrompt} fp", "f.id = fp.file_id", "INNER")
            ->where("(f.status = 'inprogress' OR f.status = 'pending')")
            ->where(['fp.status' => $fpStatus])
            ->get()->getRow();
        if (!empty($res)) {
            $this->db->table($this->table)->set(['status' => 'inprogress'])->where(['id' => $res->id])->update();
            $this->db->table($this->filePrompt)->set(['status' => 'inprogress'])->where(['id' => $res->fpId])->update();
        }
        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            return [];
        } else {
            $this->db->transCommit();
            return $res;
        }
    }

    public function getInprogressFile()
    {
        return $this->db->table("{$this->table} f")
        ->where(['f.status' => 'inprogress'])
        ->join("{$this->filePrompt} fp", 'f.id = fp.file_id', "INNER")
        ->where(['fp.status' => 'inprogress'])->countAllResults();
    }
 
}
