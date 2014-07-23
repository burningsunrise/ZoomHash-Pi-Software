#!/bin/bash
sudo killall -9 screen
sudo killall -9 bfgminer
sudo killall -9 minerd
sudo screen -wipe

bash /home/pi/start.sh

exit
