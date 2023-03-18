<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('home');
    }

    public function uploadCSV()
    {
        $csv = new \App\Libraries\CSV();
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
        return redirect()->to("/");

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

    public function cron()
    {
        $ws = new \App\Libraries\OpenAi();
        list($hasError, $resp) = $ws->createComplition("Write a 200 to 300 word article about tree trimming.");
    }
}
