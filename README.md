ZoomHash-Pi-Software
====================

Software for the company ZoomHash



Human Readable Changelog (7-22-14):<br />
ZeusMiner Support<br />
Added BFGMiner 4.3.1 Custom (Darkwinde Fork)<br />
Added chip count for ZeusMiners<br />
Added frequency for ZeusMiners<br />
Added unit count for both ZeusMiners and Gridseeds<br />
Changed how adding pools and frequency works, hopefully no more hassle with the software sometimes not recognizing your pool information<br />
minerconfig.json redone as minerconfig.dat, will instead read a regular dat file instead of a json, will help with recognizing pool information<br />
Added restart pi option (will have to manually refresh the page after the pi is rebooted)<br />
Reboot after saving settings (changing pool information) page will begin refreshing once the Pi is booted up<br />
Squashed some bugs, no more scary errors about a socket being missing, no more division by 0<br />
Added checks to make sure that Gridseed and ZeusMiners are correctly adding if both or either are available<br />
If you desire to SSH and see the software running you can do so now, added commands to easily see both ZeusMiners and Gridseeds, help information on the left sidebar for SSH commands<br />
rc.local no longer handling startup, added new script so there is no more confusion by the system (sometimes wouldn't start up CPUMiner)<br />
No longer outputs errors if there is no miner running, will wait until pool information is added<br />
Removed initial pool settings (rc.local change makes it so we don't have to have default pool information any longer)<br />
Removed a bug some users were telling me about the pool information wiping when typing in and the page auto refreshed<br />
