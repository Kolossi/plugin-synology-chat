Synology Chat plugin for Kanboard
=========================

[![Build Status](https://travis-ci.org/Kolossi/plugin-synology-chat.svg?branch=master)](https://travis-ci.org/Kolossi/plugin-synology-chat)

Receive Kanboard notifications on Synology Chat.

Author
------

- Paul Sweeney
- License MIT

Requirements
------------

- Kanboard >= 1.2.6

Installation
------------

You have the choice between 3 methods:

1. Install the plugin from the Kanboard plugin manager in one click
2. Download the zip file and decompress everything under the directory `plugins/SynologyChat`
3. Clone this repository into the folder `plugins/SynologyChat`

Note: Plugin folder is case-sensitive.

Configuration
-------------

To use this plugin, you must have access to a [Synology NAS](https://www.synology.com/products) with the [Chat package](https://www.synology.com/en-global/dsm/feature/chat) installed.

Firstly, you have to generate a new webhook url in Synology Chat :
- (**click Profile Avatar top right > Integration > Incoming Webhooks**)

You can define only one webhook url (**Settings > Integrations > Synology Chat**) to apply to all, and optionally override with a different webhook for each project and user.

### Receive individual user notifications

- Go to your user profile then choose **Integrations > Synology Chat**
- Copy and paste the project specific webhook url from Synology Chat or leave it blank if you want to use the global webhook url
- Enable Synology Chat in your user notifications **Notifications > Synology Chat**

### Receive project notifications

- Go to the project settings then choose **Integrations > Synology Chat**
- Copy and paste the user specific webhook url from Synology Chat or leave it blank if you want to use the global webhook url
- Enable Synology Chat in your project notifications **Notifications > Synology Chat**

## Troubleshooting

- Enable the debug mode
- All connection errors with the Synology Chat API are recorded in the log files `data/debug.log` or syslog
