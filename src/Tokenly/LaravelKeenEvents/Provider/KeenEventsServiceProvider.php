<?php

namespace Swapbot\Providers\KeenEvents\Provider;

use Exception;
use Illuminate\Support\ServiceProvider;
use Swapbot\Providers\KeenEvents\KeenEvents;

class KeenEventsServiceProvider extends ServiceProvider {

    public function register() {
        $this->app->bind('keenevents', function($app) {
            $queue_manager         = $app->make('Illuminate\Queue\QueueManager');
            $queue_connection_name = env('KEEN_QUEUE_CONNECTION', 'blockingbeanstalkd');
            $queue_name            = env('KEEN_QUEUE_NAME', 'keen_events');

            $connection = $queue_manager->connection($queue_connection_name);

            return new KeenEvents($connection, $queue_name);
        });
    }

}
