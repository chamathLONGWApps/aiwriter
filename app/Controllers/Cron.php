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
        if (!empty($next)) {
            log_message('debug', 'has running prompt');
            die;
        }
        $res = $fileModel->getFilePrompt();
        if (empty($res)) {
            log_message('debug', 'dying');
            die;
        }
        list($hasError, $opRes) = $openAi->createComplition($res->prompt);
        try {
            if (!$hasError) {
                $filePromptModel->saveArticle($res->id, $res->fpId, $opRes->choices[0]->message->content);
            } else {
                $filePromptModel->saveArticle($res->id, $res->fpId, "API Error occured!");
                log_message('error', 'API error');
                log_message('error', print_r($opRes, true));
            }
        } catch (\Exception $e) {
            $filePromptModel->saveArticle($res->id, $res->fpId, "API Error occured!");
            log_message('error', $e->getMessage());
        }
    }

    public function processFileSecond()
    {
        // log_message('debug', 'process file second');
        $openAi = new \App\Libraries\OpenAi();
        $fileModel = new \App\Models\FileModel();
        $filePromptModel = new \App\Models\FilePromptsModel();
        $promptInProgress = $fileModel->getInprogressFile();
        if ($promptInProgress == 0) {
            $pendingPrompt = $filePromptModel->getPrompt('inprogress', 'pending');
            if (empty($pendingPrompt)) {
                $fileModel->set(['status' => 'completed'])->where(['status' => 'inprogress'])->update();
                $prompt = $filePromptModel->getPrompt('pending', 'pending');
            } else {
                $prompt = $pendingPrompt;
            }

            if (!empty($prompt)) {
                try {
                $fileModel->update($prompt->id, ['status' => 'inprogress']);
                $filePromptModel->update($prompt->fpId, ['status' => 'inprogress']);
                list($hasError, $opRes) = $openAi->createComplition($prompt->prompt);
                if (!$hasError) {
                    $filePromptModel->saveArticle($prompt->id, $prompt->fpId, $opRes->choices[0]->text);
                } else {
                    $filePromptModel->saveArticle($prompt->id, $prompt->fpId, "API Error occured!");
                    // log_message('error', 'API error');
                    // log_message('error', print_r($opRes, true));
                }
                } catch (\Exception $e) {
                    log_message('error', $e->getMessage());
                }
            }
        }
        log_message('debug', 'inprogress');
    }
}
