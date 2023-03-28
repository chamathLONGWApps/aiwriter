<?php

namespace App\Controllers;

class Cron extends BaseController
{
    public function processFile()
    {
        try {
            $openAi = new \App\Libraries\OpenAi();
            $fileModel = new \App\Models\FileModel();
            $filePromptModel = new \App\Models\FilePromptsModel();
            $next = $fileModel->getNextFileToProcess();
            $fpState = 'pending';
            if (!empty($next)) {
                if ($next->updated_at <= date("Y-m-d h:i:s", strtotime("-2 minutes")))
                    $fpState = 'inprogress';
                else
                    die;
            }
            $res = $fileModel->getFilePrompt($fpState);
            if (empty($res)) {
                die;
            }
            list($hasError, $opRes) = $openAi->createComplition($res->prompt);
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
                    log_message('error', print_r($opRes, true));
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
