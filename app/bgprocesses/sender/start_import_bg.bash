#!/bin/bash

echo "Starting import process in background"

(nohup php parentProcess.php -i -r 1>/dev/null 2>&1)&

echo "Done!"