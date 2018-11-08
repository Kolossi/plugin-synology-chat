<?php

namespace Kanboard\Plugin\SynologyChat\Notification;

use Kanboard\Core\Base;
use Kanboard\Core\Notification\NotificationInterface;
use Kanboard\Model\TaskModel;

/**
 * Synology Chat Notification
 *
 * @package  notification
 * @author   Paul Sweeney
 */
class SynologyChat extends Base implements NotificationInterface
{
    /**
     * Send notification to a user
     *
     * @access public
     * @param  array     $user
     * @param  string    $eventName
     * @param  array     $eventData
     */
    public function notifyUser(array $user, $eventName, array $eventData)
    {
        $webhook = $this->userMetadataModel->get($user['id'], 'synologychat_webhook_url', $this->configModel->get('synologychat_webhook_url'));
        $includeLink = $this->userMetadataModel->get($user['id'], 'synologychat_include_link', $this->configModel->get('synologychat_include_link', 0));

        if (! empty($webhook)) {
            if ($eventName === TaskModel::EVENT_OVERDUE) {
                foreach ($eventData['tasks'] as $task) {
                    $project = $this->projectModel->getById($task['project_id']);
                    $eventData['task'] = $task;
                    $this->sendMessage($webhook, $includeLink, $project, $eventName, $eventData);
                }
            } else {
                $project = $this->projectModel->getById($eventData['task']['project_id']);
                $this->sendMessage($webhook, $includeLink, $project, $eventName, $eventData);
            }
        }
    }

    /**
     * Send notification to a project
     *
     * @access public
     * @param  array     $project
     * @param  string    $eventName
     * @param  array     $eventData
     */
    public function notifyProject(array $project, $eventName, array $eventData)
    {
        $webhook = $this->projectMetadataModel->get($project['id'], 'synologychat_webhook_url', $this->configModel->get('synologychat_webhook_url'));
        $includeLink = $this->projectMetadataModel->get($project['id'], 'synologychat_include_link', $this->configModel->get('synologychat_include_link', 0));

        if (! empty($webhook)) {
            $this->sendMessage($webhook, $includeLink, $project, $eventName, $eventData);
        }
    }

    /**
     * Get message to send
     *
     * @access public
     * @param  array     $project
     * @param  string    $eventName
     * @param  array     $eventData
     * @param  bool      $includeLink
     * @return array
     */
    public function getMessage(array $project, $eventName, array $eventData, $includeLink)
    {
        if ($this->userSession->isLogged()) {
            $author = $this->helper->user->getFullname();
            $title = $this->notificationModel->getTitleWithAuthor($author, $eventName, $eventData);
        } else {
            $title = $this->notificationModel->getTitleWithoutAuthor($eventName, $eventData);
        }

        $message = '*['.$project['name'].']* ';
        $message .= $title;
        $message .= ' ('.$eventData['task']['title'].')';

        if ($includeLink == 1 && $this->configModel->get('application_url') !== '') {
            $message .= ' - <';
            $message .= $this->helper->url->to('TaskViewController', 'show', array('task_id' => $eventData['task']['id'], 'project_id' => $project['id']), '', true);
            $message .= '|'.t('view the task on Kanboard').'>';
        }

        $payload = array('payload' => json_encode(array('text' => $message)));

        return $payload;
    }

    /**
     * Send message to Synology Chat
     *
     * @access protected
     * @param  string    $webhook
     * @param  bool      $includeLink
     * @param  array     $project
     * @param  string    $eventName
     * @param  array     $eventData
     */
    protected function sendMessage($webhook, $includeLink, array $project, $eventName, array $eventData)
    {
        $backoffRetries = 0;
        $tryAgain = false;

        $payload = $this->getMessage($project, $eventName, $eventData, $includeLink);

        do
        {
            $tryAgain = false;
            $body = $this->httpClient->postForm($webhook, $payload);
            
            if ($body !== '')
            {
                $response = json_decode($body, true);
                if (isset($response['success']) && $response['success']==false)
                {
                    // Synology Chat API as of v2.1.0-1228 will only accept a fairly low rate of submission, 
                    // and if it's too fast, it returns
                    // {"error":{"code":411,"errors":"create post too fast"},"success":false} 
                    if (isset($response['error']) && isset($response['error']['code']) && $response['error']['code'] == 411)
                    {
                        $tryAgain = true;
                        $backoffRetries += 1;

                        //back off for between 0-0.1 secs, adding 0.1 each retry
                        usleep(($backoffRetries-1)*100000 + rand(0,100000));
                    }
                }
            }
        } while ($tryAgain);
    }
}
