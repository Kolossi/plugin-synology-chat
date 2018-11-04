<?php

namespace Kanboard\Plugin\SynologyChat;

use Kanboard\Core\Translator;
use Kanboard\Core\Plugin\Base;

/**
 * Synology Chat Plugin
 *
 * @package  Synology Chat
 * @author   Paul Sweeney
 */
class Plugin extends Base
{
    public function initialize()
    {
        $this->template->hook->attach('template:config:integrations', 'synologyChat:config/integration');
        $this->template->hook->attach('template:project:integrations', 'synologyChat:project/integration');
        $this->template->hook->attach('template:user:integrations', 'synologyChat:user/integration');

        $this->userNotificationTypeModel->setType('synologyChat', t('SynologyChat'), '\Kanboard\Plugin\SynologyChat\Notification\SynologyChat');
        $this->projectNotificationTypeModel->setType('synologyChat', t('SynologyChat'), '\Kanboard\Plugin\SynologyChat\Notification\SynologyChat');
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getPluginDescription()
    {
        return 'Receive notifications on Synology Chat';
    }

    public function getPluginAuthor()
    {
        return 'Paul Sweeney';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/Kolossi/plugin-synology-chat';
    }

    public function getCompatibleVersion()
    {
        return '>=1.0.37';
    }
}
