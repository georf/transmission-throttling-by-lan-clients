#!/bin/bash

LEASE_HOSTS=$(cat /var/lib/misc/dnsmasq.leases | awk '{print $3}')
HOSTS=$(nmap -sP -n -oG - $LEASE_HOSTS | grep "Up" | awk '{print $2}')

for host in ${HOSTS}; do
  /usr/sbin/arp -an | grep "(${host})" | awk '{print $4}'
done
