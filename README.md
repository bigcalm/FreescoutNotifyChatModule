# Multi communication notification module for FreeScout

This module sends a notification to a channel on Discord, Slack, or Mattermost:
- when a new support ticket is created by a customer
- when an existing support ticket receives a new reply from a customer.

## Installation

1. Navigate to your Modules folder e.g. `cd /var/www/html/Modules`
2. Run `git clone https://github.com/bigcalm/FreescoutMultiCommunicationPlatformModule.git multiCommunicationPlatform`
3. Activate the module "MultiCommunicationPlatform Notifications" via the Modules page in FreeScout.
4. Create a new webhook on your communication platform of choice and copy the URL.
5. Set up the webhook URL in each mailbox you wish to receive notifications in.

## Update

1. Deactivate the MultiCommunicationPlatform module
2. Navigate to your Modules folder e.g. `cd /var/www/html/Modules`
3. Delete the MultiCommunicationPlatform module
4. Run `git clone https://github.com/bigcalm/FreescoutMultiCommunicationPlatformModule.git multiCommunicationPlatform`
5. Activate the module "MultiCommunicationPlatform Notifications" via the Modules page in FreeScout.

## Translations

- English

## Screenshots

Settings:

[TBA]

Customer created new ticket:

[TBA]

Customer updated existing ticket:

[TBA]
