<?php

namespace Tokenly\LaravelKeenEvents;

use Exception;
use Illuminate\Support\Facades\Log;

class KeenEvents {

    public function __construct($queue_connection, $queue_name) {
        $this->queue_connection = $queue_connection;
        $this->queue_name       = $queue_name;
    }

    public function send($collection, $event) {
        $this->sendEventToBeanstalkQueue($collection, $event);
    }

    protected function sendEventToBeanstalkQueue($collection, $event) {
        $entry = [
            'meta' => [
                'collection' => $collection,
            ],
            'data' => $event,
        ];

        // put notification in the queue
        $this->queue_connection->pushRaw(json_encode($entry), $this->queue_name);
    }

}
