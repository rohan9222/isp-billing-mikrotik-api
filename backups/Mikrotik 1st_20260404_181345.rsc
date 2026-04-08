# 2026-04-04 18:13:53 by RouterOS 7.22.1
# system id = GrD98k7u87K
#
/interface ethernet
set [ find default-name=ether1 ] disable-running-check=no
set [ find default-name=ether2 ] disable-running-check=no
/interface list
add name=WAN
add name=LAN
/ip pool
add name=FCOM-1_5M ranges=10.20.0.2-10.20.3.254
/interface list member
add interface=ether1 list=WAN
add interface=ether2 list=LAN
/ip address
add address=192.168.31.100/24 interface=ether2 network=192.168.31.0
/ip dhcp-client
add interface=ether1 name=client1
/ip dhcp-server
add address-pool=FCOM-1_5M interface=ether2 lease-time=10m name=DHCP
/ip firewall filter
add action=accept chain=input dst-port=8291 protocol=tcp