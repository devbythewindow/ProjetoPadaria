<?php
class LogService {
    public function log($message) {
        $logEntry = date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL;
        file_put_contents(LOG_FILE, $logEntry, FILE_APPEND);
    }
}