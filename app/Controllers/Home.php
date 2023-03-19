<?php

namespace App\Controllers;

use App\Models\FileModel;

class Home extends BaseController
{
    public function index()
    {
        $fileModel = new \App\Models\FileModel();
        $files = $fileModel->orderBy('created_at', 'DESC')->findAll();
        return view('home', ['files' => $files]);
    }

    public function uploadCSV()
    {
        log_message('debug', 'upload csv');
        // $csv = new \App\Libraries\CSV();
        $fileModel = new \App\Models\FileModel();
        $filePromptsModel = new \App\Models\FilePromptsModel();
        $file = $this->request->getFile('file');
        if (!$file->isValid())
            return $this->sendJSON(400, $file->getErrorString() . '(' . $file->getError() . ')');
        if (!$this->isValidMimeType($file))
            return view('home', ['errors' => 'Unsuported file']);
        $newName = $file->getRandomName();
        $name = $file->getName();
        $insertId = $fileModel->insert(['name' => $name]);
        log_message('debug', $insertId);
        $file->move(WRITEPATH . 'uploads', $newName);
        if ($file->hasMoved()) {
            $path = WRITEPATH . "uploads/{$newName}";
            $handle = fopen($path, "r");
            if ($handle === FALSE)
                return $this->sendJSON(400, 'Error occurred while reading the file.');
            $row = 1;
            while (($data = fgetcsv($handle)) !== FALSE) {
                $filePrompts = [];
                if ($row !== 1) {
                    $filePrompts['file_id'] = $insertId;
                    $filePrompts['topic'] = $data[0];
                    $filePrompts['prompt'] = $data[1];
                    $filePromptsModel->insert($filePrompts);
                }
                $row++;
            }
            unlink($path);
         }
        return redirect()->to(base_url());

    }

    private function isValidMimeType($file = null)
    {
        if (!$file)
            return false;
        $mimeType = $file->getClientMimeType();
        // $validMimeTypes = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        // csv mime type
        $validMimeTypes = ['application/vnd.ms-excel', 'text/csv'];
        return in_array($mimeType, $validMimeTypes);
    }

    public function downloadFile($fileId) 
    {
        $filesModel = new \App\Models\FileModel();
        $filePromptsModel = new \App\Models\FilePromptsModel();
        $csv = new \App\Libraries\Csv();
        $fileData = $filesModel->where(['id' => $fileId])->first();
        $fileContent = $filePromptsModel->where(['file_id' => $fileId])->findAll();
        // $path = WRITEPATH . 'uploads/' . $fileData['name'];
        $csvData = [];
        foreach($fileContent as $key => $content) {
            $csvData[$key][] = $content['topic']; 
            $csvData[$key][] = $content['prompt']; 
            $csvData[$key][] = $content['result']; 
        }
        return $csv->download_csv($fileData['name'], ['topic', 'prompt', 'result'], $csvData);
    }

    public function test(){
        echo "home";
    }
}
