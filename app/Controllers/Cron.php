<?php

namespace App\Controllers;

class Cron extends BaseController
{
    public function processFile() 
    {
        log_message('debug', 'cron running');
        $openAi = new \App\Libraries\OpenAi();
        $fileModel = new \App\Models\FileModel();
        $filePromptModel = new \App\Models\FilePromptsModel();
        $next = $fileModel->getNextFileToProcess();
        if(!empty($next)){
            log_message('debug', 'has running prompt');
            die;
        }
        $res = $fileModel->getFilePrompt();
        if (empty($res)){
            log_message('debug', 'dying');
            die;
        }
        list($hasError, $opRes) = $openAi->createComplition($res->prompt);
        if(!$hasError) {
            $filePromptModel->saveArticle($res->id, $res->fpId, $opRes->choices[0]->text);
        } else{
            $filePromptModel->set(['status' => 'pending'])->where(['id' => $res->fpId])->update();
            log_message('error', 'API error');
            log_message('error', print_r($opRes, true));
        }
    }
}