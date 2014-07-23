#!/bin/bash

sudo ./stop.sh
sudo cp -a /var/www/ZoomHash-Pi-Software/. /var/www
sudo chown -R pi /var/www
sudo chmod 777 /var/www/conf/minerconfig.dat
sudo rm -rf /var/www/conf/miner_conf.json
sudo rm -rf /var/www/ZoomHash-Pi-Software/

echo "Your Pi will now reboot and you will need to re-enter your settings!"
sudo shutdown -r now
exit
