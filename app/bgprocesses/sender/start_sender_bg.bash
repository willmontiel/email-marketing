#!/bin/bash

echo "Starting sender process in background"

(nohup php parentProcess.php -m -r 1>/dev/null 2>&1)&

echo "Done!"