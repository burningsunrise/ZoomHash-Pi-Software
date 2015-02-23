#!/bin/bash

set -e


file="/var/www/conf/minerconfig.dat"
foo=" -G"

#check if zeus exists
if [ -e /dev/ttyUSB0 ]
	then
		zeus_exist=$(ls /dev/ttyUSB*)
		if [ -f "$file" ]
			then
				old_IFS=$IFS
				IFS=$'\n'
				line=($(cat $file)) # array
				IFS=$old_IFS
				
				for i in `seq 0 ${line[0]}`; do
				countz+="-S zeus:/dev/ttyUSB"$i" ";done
				count2z=${countz%","}
		fi
fi

#check if gridseed exists
if [ -e /dev/ttyACM0 ]
	then
		grid_exist=$(ls -C /dev/ttyACM*)
		if [ -f "$file" ]
			then
				old_IFS=$IFS
				IFS=$'\n'
				line=($(cat $file)) # array
				IFS=$old_IFS
				
				for i in `seq 0 ${line[1]}`; do
				count+="/dev/ttyACM"$i",";done
				count2=${count%","}
		fi
		
fi

if [ -n "$zeus_exist" ]
	then 
		if [ -f "$file" ]
			then
				old_IFS=$IFS
				IFS=$'\n'
				line=($(cat $file)) # array
				IFS=$old_IFS

				minercount=$((${line[0]}))

				cd /var/www/bfgminer

					sudo screen -dmS zeus ./bfgminer --scrypt --log-file /var/www/bfgminer/miner.log --api-listen --api-network --api-port 4025 -o ${line[3]} -u ${line[4]} -p ${line[5]} --zeus-cc ${line[6]} --zeus-clk ${line[2]} $count2z
		fi
fi

if [ -n "$grid_exist" ]
	then
		if [ -f "$file" ]
			then
			old_IFS=$IFS
			IFS=$'\n'
			line=($(cat $file))
			IFS=$old_IFS
		
			minercount=$((${line[1]}))
		
			cd /var/www/cpu-miner
		
				sudo screen -dmS grid ./minerd  -G $count2 --api-port=4027 -F ${line[7]} --url=${line[3]} -u ${line[4]} -p ${line[5]} --log=/var/www/cpu-miner/miner.log
		fi
		
fi

exit 0
