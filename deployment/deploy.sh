#!/bin/sh
ssh -T particle <<!
sudo -u shoperella sh -c 'cd ~/site && git pull && bin/console cache:clear --env=prod'
!
