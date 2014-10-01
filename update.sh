#!/bin/bash

sudo ./stop.sh
echo "Please wait while the changes are made to your system, may take a minute or two!"
sudo cp -a /var/www/ZoomHash-Pi-Software/. /var/www
sudo cp -a /var/www/ZoomHash-Pi-Software/startup.sh /home/pi/start.sh
sudo chown -R pi /var/www
sudo chmod 777 /var/www/conf/minerconfig.dat
sudo rm -rf /var/www/conf/miner_conf.json
sudo rm -rf /var/www/ZoomHash-Pi-Software/
sudo rm -rf /var/www/startup.sh

echo "Your Pi will now reboot and you will need to re-enter your settings!"
sudo shutdown -r now
exit
