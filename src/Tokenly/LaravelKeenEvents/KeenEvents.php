<?php

namespace Tokenly\LaravelKeenEvents;

use Exception;
use Illuminate\Support\Facades\Log;

class KeenEvents {

    public function __construct($queue_connection, $queue_name, $active) {
        $this->queue_connection = $queue_connection;
        $this->queue_name       = $queue_name;
        $this->active           = $active;
    }

    public function isActive() {
        return $this->active;
    }

    public function sendKeenEvent($collection, $event) {
        if (!$this->active) { return; }

        $this->sendEventToBeanstalkQueue($event, [
            'jobType'    => 'keen',
            'collection' => $collection,
        ]);
    }

    public function sendSlackEvent($data_or_title, $text_or_fields) {
        if (!$this->active) { return; }

        if (is_array($text_or_fields)) {
            $fields = $text_or_fields;
        } else {
            $fields = [['title' => 'Description', 'value' => $text_or_fields]];
        }
        $fields = $this->normalizeFields($fields);

        if (is_array($data_or_title)) {
            $event = $data_or_title;
        } else {
            $event = ['title' => $data_or_title];
        }

        $event['fields'] = $fields;

        if (!isset($event['color'])) { $event['color'] = 'good'; }

        $this->sendEventToBeanstalkQueue($event, [
            'jobType'    => 'slack',
        ]);
    }

    protected function normalizeFields($fields_in) {
        $fields_out = [];
        foreach($fields_in as $field) {
            if (!isset($field['short'])) {
                $field['short'] = (strlen($field['value']) < 25);
            }
            $fields_out[] = $field;
        }
        return $fields_out;
    }

    protected function sendEventToBeanstalkQueue($event, $meta) {
        $entry = [
            'meta' => $meta,
            'data' => $event,
        ];

        // put notification in the queue
        $this->queue_connection->pushRaw(json_encode($entry), $this->queue_name);
    }

}
