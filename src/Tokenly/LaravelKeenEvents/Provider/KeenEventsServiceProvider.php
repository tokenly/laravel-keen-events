<?php

namespace Tokenly\LaravelKeenEvents\Provider;

use Exception;
use Illuminate\Support\ServiceProvider;
use Tokenly\LaravelKeenEvents\KeenEvents;

class KeenEventsServiceProvider extends ServiceProvider {

    public function register() {
        $this->app->bind('keenevents', function($app) {
            $queue_manager         = $app->make('Illuminate\Queue\QueueManager');
            $queue_connection_name = env('KEEN_QUEUE_CONNECTION',      'blockingbeanstalkd');
            $queue_name            = env('KEEN_QUEUE_NAME',            'keen_events');
            $keen_active           = env('KEEN_EVENTS_ACTIVE',         false);
            $slack_active          = env('SLACK_NOTIFICATIONS_ACTIVE', false);

            $connection = $queue_manager->connection($queue_connection_name);

            return new KeenEvents($connection, $queue_name, $keen_active, $slack_active);
        });
    }

}
