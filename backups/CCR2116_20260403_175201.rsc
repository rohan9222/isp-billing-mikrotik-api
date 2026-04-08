# 2026-04-03 23:52:50 by RouterOS 7.18.2
# software id = S7LM-GG16
#
# model = CCR2116-12G-4S+
# serial number = HEG08Y769DW
/interface bridge
add name=LoopBack
/interface ethernet
set [ find default-name=sfp-sfpplus1 ] name=1.SFP+1_Uplink_SCL
set [ find default-name=sfp-sfpplus4 ] name=1.SFP+4_Backup_Uplink_SCL
set [ find default-name=sfp-sfpplus3 ] name=2.sfp-sfpplus3-1009
set [ find default-name=ether11 ] name=3.ether11_Cdata
set [ find default-name=ether12 ] name=4.ether12_Btpon
set [ find default-name=ether10 ] name=5.ether10_Vsol-4P
set [ find default-name=ether2 ] name=ether2_Log_Server
/interface l2tp-client
add connect-to=103.124.238.128 name=FTP user=fcom
/interface vlan
add interface=1.SFP+1_Uplink_SCL name=1.2254.INT vlan-id=2254
add interface=1.SFP+1_Uplink_SCL name=1.2255.GGC vlan-id=2255
add interface=1.SFP+1_Uplink_SCL name=1.2256.FNA vlan-id=2256
add interface=1.SFP+1_Uplink_SCL name=1.2257.BDIX vlan-id=2257
add interface=1.SFP+1_Uplink_SCL name=1.2258.SCL_CDN vlan-id=2258
add disabled=yes interface=2.sfp-sfpplus3-1009 name=11.To_Access_1009_Ether \
    vlan-id=11
add interface=4.ether12_Btpon name=100_BTPON_PON1 vlan-id=100
add interface=4.ether12_Btpon name=101_BTPON_PON2 vlan-id=101
add interface=4.ether12_Btpon name=102_BTPON_PON3 vlan-id=102
add interface=4.ether12_Btpon name=103_BTPON_PON4 vlan-id=103
add interface=1.SFP+1_Uplink_SCL name=110_8P_VSol-P1 vlan-id=110
add interface=1.SFP+1_Uplink_SCL name=111_8P_VSol-P2 vlan-id=111
add interface=1.SFP+1_Uplink_SCL name=112_8P_VSol-P3 vlan-id=112
add interface=1.SFP+1_Uplink_SCL name=113_8P_VSol-P4 vlan-id=113
add interface=1.SFP+1_Uplink_SCL name=114_8P_VSol-P5 vlan-id=114
add interface=1.SFP+1_Uplink_SCL name=115_8P_VSol-P6 vlan-id=115
add interface=1.SFP+1_Uplink_SCL name=116_8P_VSol-P7 vlan-id=116
add interface=1.SFP+1_Uplink_SCL name=117_8P_VSol-P8 vlan-id=117
add interface=3.ether11_Cdata name=200_CDATA_PON1 vlan-id=200
add interface=3.ether11_Cdata name=201_CDATA_PON2 vlan-id=201
add interface=3.ether11_Cdata name=202_CDATA_PON3 vlan-id=202
add interface=3.ether11_Cdata name=203_CDATA_PON4 vlan-id=203
add interface=5.ether10_Vsol-4P name=301_Vsol_Pon_1 vlan-id=301
add interface=5.ether10_Vsol-4P name=302_Vsol_Pon_2 vlan-id=302
add interface=5.ether10_Vsol-4P name=303_Vsol_Pon_3 vlan-id=303
add interface=5.ether10_Vsol-4P name=304_Vsol_Pon_4 vlan-id=304
add interface=4.ether12_Btpon name=BTPON_MGMT vlan-id=53
add interface=3.ether11_Cdata name=C-Data_MGMT vlan-id=54
add interface=5.ether10_Vsol-4P name=Vsol_MGMT-4P vlan-id=52
add interface=1.SFP+1_Uplink_SCL name=Vsol_MGMT-8Port vlan-id=51
/interface wireless security-profiles
set [ find default=yes ] supplicant-identity=MikroTik
/ip firewall layer7-protocol
add name=Tiktok regexp="^.+(tiktokcdn.com).*\$"
/ip pool
add name=PPTP ranges=10.90.0.0/30
add name=FCOM-1_5M ranges=10.20.0.2-10.20.3.254
add name=FCOM-2_8M ranges=10.20.4.2-10.20.5.254
add name=FCOM-3_12M ranges=10.20.6.2-10.20.7.254
add name=FCOM-4_20M ranges=10.20.8.2-10.20.9.254
add name=FCOM-5_25M ranges=10.20.10.2-10.20.11.254
add name=FCOM-6_30M ranges=10.20.12.2-10.20.13.254
add name=FCOM-7_40M ranges=10.20.14.2-10.20.15.254
add name=VPN ranges=10.91.0.10-10.91.0.30
add name=IPSec ranges=10.11.220.2-10.11.220.254
/ip smb users
set [ find default=yes ] disabled=yes
/port
set 0 name=serial0
/ppp profile
set *0 dns-server=8.8.8.8,8.8.4.4 local-address=10.20.0.1 on-up=":local remote\
    Addr \$\"remote-address\"\r\
    \n:local callerId \$\"caller-id\"\r\
    \n:log info \"PPPLOG \$user \$callerId \$remoteAddr\";\r\
    \n"
add dns-server=8.8.8.8,8.8.4.4 local-address=10.20.0.1 name=FCOM-1_5M on-up=":\
    local remoteAddr \$\"remote-address\"\r\
    \n:local callerId \$\"caller-id\"\r\
    \n:log info \"PPPLOG \$user \$callerId \$remoteAddr\";\r\
    \n" only-one=yes remote-address=FCOM-1_5M
add dns-server=8.8.8.8,8.8.4.4 local-address=10.20.8.1 name=FCOM-2_8M on-up=":\
    local remoteAddr \$\"remote-address\"\r\
    \n:local callerId \$\"caller-id\"\r\
    \n:log info \"PPPLOG \$user \$callerId \$remoteAddr\";\r\
    \n" only-one=yes remote-address=FCOM-2_8M
add dns-server=8.8.8.8,8.8.4.4 local-address=10.20.16.1 name=FCOM-3_12M \
    on-up=":local remoteAddr \$\"remote-address\"\r\
    \n:local callerId \$\"caller-id\"\r\
    \n:log info \"PPPLOG \$user \$callerId \$remoteAddr\";\r\
    \n" only-one=yes remote-address=FCOM-3_12M
add dns-server=8.8.8.8,8.8.4.4 local-address=10.20.24.1 name=FCOM-4_20M \
    on-up=":local remoteAddr \$\"remote-address\"\r\
    \n:local callerId \$\"caller-id\"\r\
    \n:log info \"PPPLOG \$user \$callerId \$remoteAddr\";\r\
    \n" only-one=yes remote-address=FCOM-4_20M
add dns-server=8.8.8.8,8.8.4.4 local-address=10.20.32.1 name=FCOM-5_25M \
    on-up=":local remoteAddr \$\"remote-address\"\r\
    \n:local callerId \$\"caller-id\"\r\
    \n:log info \"PPPLOG \$user \$callerId \$remoteAddr\";\r\
    \n" only-one=yes remote-address=FCOM-5_25M
add dns-server=8.8.8.8,8.8.4.4 local-address=10.20.40.1 name=FCOM-6_30M \
    on-up=":local remoteAddr \$\"remote-address\"\r\
    \n:local callerId \$\"caller-id\"\r\
    \n:log info \"PPPLOG \$user \$callerId \$remoteAddr\";\r\
    \n" only-one=yes remote-address=FCOM-6_30M
add dns-server=8.8.8.8,8.8.4.4 local-address=10.20.48.1 name=FCOM-7_40M \
    on-up=":local remoteAddr \$\"remote-address\"\r\
    \n:local callerId \$\"caller-id\"\r\
    \n:log info \"PPPLOG \$user \$callerId \$remoteAddr\";\r\
    \n" only-one=yes remote-address=FCOM-7_40M
add dns-server=8.8.8.8,8.8.4.4 local-address=10.91.0.1 name=FCNETVPN \
    remote-address=VPN
set *FFFFFFFE dns-server=8.8.8.8,8.8.4.4
/queue simple
add name=12M-INT target=10.20.6.0/23
/queue type
add kind=pcq name=5M-Down pcq-classifier=dst-address pcq-limit=100KiB \
    pcq-rate=7M pcq-total-limit=1000KiB
add kind=pcq name=5M-Up pcq-classifier=src-address pcq-limit=100KiB pcq-rate=\
    100M pcq-total-limit=1000KiB
add kind=pcq name=8M-Down pcq-classifier=dst-address pcq-limit=100KiB \
    pcq-rate=8M pcq-total-limit=1000KiB
add kind=pcq name=8M-Up pcq-classifier=src-address pcq-limit=100KiB pcq-rate=\
    100M pcq-total-limit=1000KiB
add kind=pcq name=12M-Up pcq-classifier=src-address pcq-limit=100KiB \
    pcq-rate=100M pcq-total-limit=1000KiB
add kind=pcq name=12M-Down pcq-classifier=dst-address pcq-limit=100KiB \
    pcq-rate=12M pcq-total-limit=1000KiB
add kind=pcq name=20M-Down pcq-classifier=dst-address pcq-limit=100KiB \
    pcq-rate=20M pcq-total-limit=1000KiB
add kind=pcq name=20M-Up pcq-classifier=src-address pcq-limit=100KiB \
    pcq-rate=100M pcq-total-limit=1000KiB
add kind=pcq name=25M-Up pcq-classifier=src-address pcq-limit=100KiB \
    pcq-rate=100M pcq-total-limit=1000KiB
add kind=pcq name=25M-Down pcq-classifier=dst-address pcq-limit=100KiB \
    pcq-rate=25M pcq-total-limit=1000KiB
add kind=pcq name=30M-Down pcq-classifier=dst-address pcq-limit=100KiB \
    pcq-rate=30M pcq-total-limit=1000KiB
add kind=pcq name=30M-Up pcq-classifier=src-address pcq-limit=100KiB \
    pcq-rate=100M pcq-total-limit=1000KiB
add kind=pcq name=40M-Up pcq-classifier=src-address pcq-limit=100KiB \
    pcq-rate=100M pcq-total-limit=1000KiB
add kind=pcq name=40M-Down pcq-classifier=dst-address pcq-limit=100KiB \
    pcq-rate=40M pcq-total-limit=1000KiB
add kind=pcq name=50M-Down-Cache pcq-classifier=dst-address pcq-limit=100KiB \
    pcq-rate=50M pcq-total-limit=1000KiB
add kind=pcq name=50M-Up-Cache pcq-classifier=src-address pcq-limit=100KiB \
    pcq-rate=50M pcq-total-limit=1000KiB
add kind=pcq name=100M-Down-Cache pcq-classifier=dst-address pcq-limit=100KiB \
    pcq-rate=100M pcq-total-limit=1000KiB
add kind=pcq name=100M-Up-Cache pcq-classifier=src-address pcq-limit=100KiB \
    pcq-rate=100M pcq-total-limit=1000KiB
add kind=pcq name=50M-Up-CDN pcq-classifier=src-address pcq-limit=100KiB \
    pcq-rate=50M pcq-total-limit=1000KiB
add kind=pcq name=50M-Down-CDN pcq-classifier=dst-address pcq-limit=100KiB \
    pcq-rate=50M pcq-total-limit=1000KiB
add kind=pcq name=30M-Up-CDN pcq-classifier=src-address pcq-limit=100KiB \
    pcq-rate=30M pcq-total-limit=1000KiB
add kind=pcq name=30M-Down-CDN pcq-classifier=dst-address pcq-limit=100KiB \
    pcq-rate=30M pcq-total-limit=1000KiB
add kind=pcq name=10M-Down pcq-classifier=dst-address pcq-limit=100KiB \
    pcq-rate=10M pcq-total-limit=1000KiB
add kind=pcq name=10M-Up pcq-classifier=src-address pcq-limit=100KiB \
    pcq-rate=100M pcq-total-limit=1000KiB
add kind=pcq name=70M-Down-Cache pcq-classifier=dst-address pcq-limit=100KiB \
    pcq-rate=70M pcq-total-limit=1000KiB
add kind=pcq name=70M-Up-Cache pcq-classifier=src-address pcq-limit=100KiB \
    pcq-rate=70M pcq-total-limit=1000KiB
set 31 pcq-limit=100KiB pcq-total-limit=1000KiB
set 32 pcq-limit=100KiB pcq-total-limit=1000KiB
/queue interface
set "3.ether11_Cdata" queue=multi-queue-ethernet-default
set "4.ether12_Btpon" queue=multi-queue-ethernet-default
set "5.ether10_Vsol-4P" queue=multi-queue-ethernet-default
/queue simple
add max-limit=1G/1G name=Google-Meta-Global packet-marks=fna-ggc-glob-pkt \
    priority=2/2 queue=50M-Up-Cache/50M-Down-CDN target=10.20.0.0/19
add dst=148.222.64.0/22 max-limit=200M/200M name=Free_Fire priority=1/1 \
    queue=40M-Up/40M-Down target=""
add dst=154.85.74.0/24 name=TikTok queue=50M-Up-CDN/50M-Down-CDN target=""
add dst=1.2257.BDIX name=All-Bdix queue=70M-Up-Cache/70M-Down-Cache target=""
add dst=1.2255.GGC name=All-GGC queue=70M-Up-Cache/70M-Down-Cache target=""
add dst=1.2256.FNA name=All-FNA queue=70M-Up-Cache/70M-Down-Cache target=""
add dst=1.2258.SCL_CDN name=All-CDN queue=50M-Up-Cache/50M-Down-Cache target=\
    ""
add name=5M-INT queue=5M-Up/5M-Down target=10.20.0.0/22
add name=8M-INT queue=8M-Up/8M-Down target=10.20.4.0/23
add name=20M-INT queue=20M-Up/20M-Down target=10.20.8.0/23
add name=25M-INT queue=25M-Up/25M-Down target=10.20.10.0/23
add name=30M-INT queue=30M-Up/30M-Down target=10.20.12.0/23
add name=40M-INT queue=40M-Up/40M-Down target=10.20.14.0/23
/routing bgp template
set default as=64512 disabled=no router-id=10.31.54.34 routing-table=main
/snmp community
set [ find default=yes ] addresses=172.24.7.254/32,172.24.7.250/32 name=\
    FriendsComNms write-access=yes
/system logging action
add name=maestrolog remote=10.10.10.2 remote-log-format=syslog \
    syslog-facility=local6 target=remote
add name=systemlog remote=10.10.10.2 remote-log-format=syslog \
    syslog-facility=syslog target=remote
/user group
set write policy="local,telnet,ssh,reboot,read,write,test,sniff,sensitive,api,\
    romon,rest-api,!ftp,!policy,!winbox,!password,!web"
/interface bridge port
add bridge=*43 interface=1.SFP+1_Uplink_SCL
add bridge=*43 interface=1.SFP+4_Backup_Uplink_SCL
/ip firewall connection tracking
set tcp-established-timeout=1h udp-timeout=10s
/interface l2tp-server server
set default-profile=FCNETVPN enabled=yes one-session-per-host=yes use-ipsec=\
    yes
/interface ovpn-server server
add mac-address=FE:58:A7:85:34:0E name=ovpn-server1
/interface pppoe-server server
add disabled=no interface=100_BTPON_PON1 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service1
add disabled=no interface=101_BTPON_PON2 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service2
add disabled=no interface=102_BTPON_PON3 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service3
add disabled=no interface=103_BTPON_PON4 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service4
add disabled=no interface=200_CDATA_PON1 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service5
add disabled=no interface=201_CDATA_PON2 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service6
add disabled=no interface=202_CDATA_PON3 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service7
add disabled=no interface=203_CDATA_PON4 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service8
add disabled=no interface=301_Vsol_Pon_1 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service9
add disabled=no interface=302_Vsol_Pon_2 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service10
add disabled=no interface=303_Vsol_Pon_3 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service11
add disabled=no interface=304_Vsol_Pon_4 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service12
add disabled=no interface=110_8P_VSol-P1 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service13
add disabled=no interface=111_8P_VSol-P2 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service14
add disabled=no interface=112_8P_VSol-P3 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service15
add disabled=no interface=113_8P_VSol-P4 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service16
add disabled=no interface=114_8P_VSol-P5 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service17
add disabled=no interface=115_8P_VSol-P6 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service18
add disabled=no interface=116_8P_VSol-P7 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service19
add disabled=no interface=117_8P_VSol-P8 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service20
add disabled=no interface=ether5 max-mru=1480 max-mtu=1480 \
    one-session-per-host=yes service-name=service21
/interface pptp-server server
# PPTP connections are considered unsafe, it is suggested to use a more modern VPN protocol instead
set authentication=pap,chap,mschap1,mschap2 enabled=yes
/ip address
add address=157.119.186.254/30 disabled=yes interface=1.2254.INT network=\
    157.119.186.252
add address=10.10.101.234/30 interface=1.2255.GGC network=10.10.101.232
add address=103.199.86.82/30 interface=1.2256.FNA network=103.199.86.80
add address=10.31.54.34/30 interface=1.2257.BDIX network=10.31.54.32
add address=172.24.7.1/24 interface=ether2_Log_Server network=172.24.7.0
add address=10.10.10.1/30 interface=ether2_Log_Server network=10.10.10.0
add address=10.10.92.1/30 comment="To Access" interface=\
    11.To_Access_1009_Ether network=10.10.92.0
add address=103.96.71.79/31 interface=1.2258.SCL_CDN network=103.96.71.78
add address=157.119.186.253/30 interface=1.2254.INT network=157.119.186.252
add address=103.96.69.96/30 interface=LoopBack network=103.96.69.96
add address=103.108.147.121 interface=LoopBack network=103.108.147.121
add address=10.10.11.1/30 interface=Vsol_MGMT-8Port network=10.10.11.0
add address=10.10.11.5/30 interface=Vsol_MGMT-4P network=10.10.11.4
add address=10.10.11.13/30 interface=C-Data_MGMT network=10.10.11.12
add address=10.10.11.9/30 interface=BTPON_MGMT network=10.10.11.8
add address=157.119.186.108 comment="For Website" interface=LoopBack network=\
    157.119.186.108
/ip cloud
set update-time=no
/ip dns
set servers=8.8.8.8,8.8.4.4
/ip firewall address-list
add address=23.246.0.0/16 list=Fast-IP
add address=45.57.0.0/16 list=Fast-IP
add address=202.150.221.0/24 list=Fast-IP
add address=45.57.72.0/21 list=Fast-IP
add address=23.246.48.0/21 list=Fast-IP
add address=182.79.0.0/16 list=Fast-IP
add address=52.88.144.0/20 list=Fast-IP
add address=atlanta.speed.googlefiber.net list=Fast-IP
add address=sg-ovh-singapore-01-10g-1.nperf.net list=Fast-IP
add address=sg-ovh-singapore-01-10g.nperf.net list=Fast-IP
add address=test-ipv4.nperf.net list=Fast-IP
add address=proof.ovh.net list=Fast-IP
add address=speedtest.googlefiber.net list=Fast-IP
add address=kansas.speed.googlefiber.net list=Fast-IP
add address=speedtest.com.sg.prod.hosts.ooklaserver.net list=Fast-IP
add address=www.speedtest.com.sg list=Fast-IP
add address=c.speedtestcustom.com list=Fast-IP
add address=fonts.googleapis.com list=Fast-IP
add address=fonts.gstatic.com list=Fast-IP
add address=logos.speedtestcustom.com list=Fast-IP
add address=speedtestsg.speedtestcustom.com list=Fast-IP
add address=www.google-analytics.com list=Fast-IP
add address=fast.com comment=logos.speedtestcustom.com list=Fast-IP
add address=157.240.1.62 comment=fonts.googleapis.com list=Fast-IP
add address=136.42.34.0/24 comment=atlanta.speed.googlefiber.net list=Fast-IP
add address=23.32.0.0/11 list=CDN
add address=23.192.0.0/11 list=CDN
add address=184.84.0.0/14 list=CDN
add address=154.85.74.0/24 list=CDN
add address=103.243.80.0/24 list=CDN
add address=45.57.74.213 list=Fast-IP
add address=202.1202.150.221.170 list=Fast-IP
add address=142.250.0.0/15 comment=Google list=CDN
add address=74.125.0.0/16 comment=Google list=CDN
add address=172.217.0.0/16 comment=Google list=CDN
add address=173.254.0.0/16 comment=Google list=CDN
add address=216.0.0.0/8 comment=Google list=CDN
add address=209.0.0.0/8 comment=Google list=CDN
add address=api.fast.com list=Fast-IP
add address=ichnaea-web.netflix.com list=Fast-IP
add address=ipv4-c027-waw001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c029-waw001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c038-sin001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c046-sin001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c049-sin001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c061-waw001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c062-sin001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c063-waw001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c068-sin001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c069-sin001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c072-sin001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c074-sin001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c093-fra002-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c101-fra002-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c102-fra002-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c110-fra002-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c111-fra002-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c117-fra002-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c160-fra002-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c042-sin001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c043-sin001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c047-waw001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c054-waw001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c055-sin001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c056-sin001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c059-sin001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c064-sin001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c067-sin001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c071-sin001-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c098-fra002-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c100-fra002-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c105-fra002-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c119-fra002-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c120-fra002-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c165-fra002-ix.1.oca.nflxvideo.net list=Fast-IP
add address=ipv4-c173-fra002-ix.1.oca.nflxvideo.net list=Fast-IP
add address=10.20.0.0/20 comment="CCR2116 User NAT" list=User_NAT
add address=ipv4-c001-bho001-bhartiairtel-isp.1.oca.nflxvideo.net list=\
    Fast-IP
add address=ipv4-c001-del002-bhartiairtel-isp.1.oca.nflxvideo.net list=\
    Fast-IP
add address=ipv4-c005-maa001-bhartiairtel-isp.1.oca.nflxvideo.net list=\
    Fast-IP
add address=ipv4-c010-del001-bhartiairtel-isp.1.oca.nflxvideo.net list=\
    Fast-IP
add address=182.79.161.0/24 comment="Fast Bharti Airtel" list=Fast-IP
add address=192.168.255.0/30 comment="Saiful Bhai" list=User_NAT
add address=172.16.0.0/16 list=User_NAT
add address=154.85.65.0/24 list=CDN
add address=172.24.7.254 comment=NMS list=User_NAT
add address=172.24.7.5 comment=Proxmox list=User_NAT
add address=172.24.7.253 comment=DNS list=User_NAT
add address=10.10.10.0/30 comment="Log Server" list=User_NAT
add address=10.10.20.0/24 comment="Allow Second Router" list=User_NAT
add address=172.30.8.0/21 list=User_NAT
add address=114.130.116.0/22 list=hsbn_ix
add address=123.49.0.0/18 list=hsbn_ix
add address=131.186.48.0/22 list=hsbn_ix
add address=114.130.240.0/21 list=hsbn_ix
add address=180.211.213.0/24 list=hsbn_ix
add address=10.10.92.0/30 comment="Saiful Bhai" list=User_NAT
add address=172.16.0.2-172.16.7.254 list=FCOM-1_5M
add address=172.16.8.2-172.16.15.254 list=FCOM-2_8M
add address=172.16.16.2-172.16.23.254 list=FCOM-3_12M
add address=172.16.24.2-172.16.31.254 list=FCOM-4_20M
add address=172.16.32.2-172.16.39.254 list=FCOM-5_25M
add address=172.16.40.2-172.16.47.254 list=FCOM-6_30M
add address=172.16.48.2-172.16.55.254 list=FCOM-7_40M
add address=172.24.7.250 comment=Cacti list=User_NAT
add address=10.11.220.2-10.11.220.254 comment=IPSecVPN list=User_NAT
add address=10.16.100.0/22 list=FTP
add address=172.19.178.0/24 list=FTP
add address=172.16.29.0/24 list=FTP
add address=15.1.1.0/24 list=FTP
add address=15.1.2.0/24 list=FTP
add address=15.1.3.0/24 list=FTP
add address=15.1.4.0/24 list=FTP
add address=15.1.5.0/24 list=FTP
add address=15.2.2.0/24 list=FTP
add address=103.89.248.0/24 list=FTP
add address=154.95.93.0/24 list=FTP
add address=103.170.205.0/24 list=FTP
add address=157.119.186.108/30 list=NO-NAT
add address=157.119.186.84/30 list=NO-NAT
add address=142.250.0.0/15 list=Google-Facebook-Global
add address=74.125.0.0/16 list=Google-Facebook-Global
add address=172.217.0.0/16 list=Google-Facebook-Global
add address=173.254.0.0/16 list=Google-Facebook-Global
add address=216.0.0.0/8 list=Google-Facebook-Global
add address=209.0.0.0/8 list=Google-Facebook-Global
add address=157.240.0.0/16 list=Google-Facebook-Global
add address=31.13.0.0/16 list=Google-Facebook-Global
add address=57.144.0.0/14 list=Google-Facebook-Global
add address=154.85.74.0/24 comment=Tiktok list=Google-Facebook-Global
/ip firewall filter
add action=accept chain=input comment="Allow API Access" dst-port=8067 \
    protocol=tcp
add action=drop chain=forward dst-port=25 protocol=tcp
add action=fasttrack-connection chain=forward comment="Router Range Issue" \
    connection-state=established,related hw-offload=yes
add action=accept chain=forward comment="Router Range Issue" \
    connection-state=established,related
add action=accept chain=forward comment="Router Range Issue" \
    connection-state=new
add action=drop chain=forward comment="Router Range Issue" connection-state=\
    invalid
/ip firewall mangle
add action=change-mss chain=forward new-mss=1310 protocol=tcp tcp-flags=syn
add action=mark-connection chain=forward new-connection-mark=\
    fna-ggc-glob-conn src-address-list=Google-Facebook-Global
add action=mark-connection chain=forward dst-address-list=\
    Google-Facebook-Global new-connection-mark=fna-ggc-glob-conn
add action=mark-packet chain=forward connection-mark=fna-ggc-glob-conn \
    new-packet-mark=fna-ggc-glob-pkt src-address-list=Google-Facebook-Global
add action=mark-packet chain=forward connection-mark=fna-ggc-glob-conn \
    dst-address-list=Google-Facebook-Global new-packet-mark=fna-ggc-glob-pkt
add action=log chain=prerouting connection-state=established protocol=tcp \
    src-address-list=User_NAT tcp-flags=fin
/ip firewall nat
add action=src-nat chain=srcnat comment="For www.onlinereturn.net" disabled=\
    yes dst-address=151.106.123.34 to-addresses=157.119.186.108
add action=dst-nat chain=dstnat disabled=yes dst-port=53 protocol=udp \
    to-addresses=103.124.236.20 to-ports=53
add action=dst-nat chain=dstnat disabled=yes dst-port=53 protocol=tcp \
    to-addresses=103.124.236.20 to-ports=53
add action=src-nat chain=srcnat disabled=yes out-interface=1.2257.BDIX \
    to-addresses=157.119.186.108
add action=src-nat chain=srcnat out-interface=1.2257.BDIX src-address-list=\
    !NO-NAT to-addresses=103.108.147.121
add action=src-nat chain=srcnat out-interface=1.2254.INT src-address-list=\
    !NO-NAT to-addresses=103.96.69.96/30
add action=masquerade chain=srcnat src-address-list=!NO-NAT to-addresses=\
    157.119.186.108/30
add action=masquerade chain=srcnat disabled=yes dst-address-list=FTP \
    out-interface=FTP
add action=masquerade chain=srcnat disabled=yes src-address=10.91.0.0/24
add action=dst-nat chain=dstnat disabled=yes dst-address=202.150.221.170 \
    dst-port=8080 protocol=tcp to-addresses=103.124.236.12 to-ports=8080
add action=dst-nat chain=dstnat comment="Server Post Forword 8006" disabled=\
    yes dst-address=157.119.186.253 dst-port=8006 protocol=tcp to-addresses=\
    172.24.7.5 to-ports=8006
add action=dst-nat chain=dstnat comment="DNS SSH Port Forward" disabled=yes \
    dst-address=157.119.186.253 dst-port=8076 protocol=tcp to-addresses=\
    172.24.7.252 to-ports=8076
add action=dst-nat chain=dstnat comment="Grafana For DNS" disabled=yes \
    dst-address=157.119.186.253 dst-port=3000 protocol=tcp to-addresses=\
    172.24.7.252 to-ports=3000
add action=dst-nat chain=dstnat disabled=yes dst-address=157.119.186.253 \
    dst-port=2020 protocol=tcp to-addresses=172.24.7.250 to-ports=22
add action=dst-nat chain=dstnat comment="NMS Web Access" dst-address=\
    157.119.186.253 dst-port=8078 protocol=tcp to-addresses=172.24.7.254 \
    to-ports=8078
add action=dst-nat chain=dstnat comment="NMS SSH Port Forword" disabled=yes \
    dst-address=157.119.186.253 dst-port=8077 protocol=tcp to-addresses=\
    172.24.7.254 to-ports=8077
add action=dst-nat chain=dstnat comment=EasyBilling disabled=yes dst-address=\
    157.119.186.253 dst-port=51922 protocol=tcp to-addresses=10.10.92.2 \
    to-ports=51922
add action=dst-nat chain=dstnat comment="DNS SSH Port Forword" disabled=yes \
    dst-address=157.119.186.254 dst-port=8077 protocol=tcp to-addresses=\
    172.24.7.253 to-ports=8077
add action=dst-nat chain=dstnat comment="DNS Web Port Forword" disabled=yes \
    dst-address=157.119.186.254 dst-port=80 protocol=tcp to-addresses=\
    172.24.7.253 to-ports=80
add action=dst-nat chain=dstnat comment="Server Post Forword" disabled=yes \
    dst-address=157.119.186.253 dst-port=22 protocol=tcp to-addresses=\
    172.24.7.5 to-ports=22
add action=dst-nat chain=dstnat comment="To Access Router" disabled=yes \
    dst-address=157.119.186.253 dst-port=1310 protocol=tcp to-addresses=\
    10.10.92.2 to-ports=1310
add action=dst-nat chain=dstnat comment="1009 Billing Add" disabled=yes \
    dst-address=157.119.186.253 dst-port=8099 protocol=tcp to-addresses=\
    10.10.92.2 to-ports=8099
add action=dst-nat chain=dstnat comment="1009 Billing Add" disabled=yes \
    dst-address=157.119.186.253 dst-port=8720 protocol=tcp to-addresses=\
    10.10.92.2 to-ports=8720
add action=dst-nat chain=dstnat dst-address=157.119.186.253 dst-port=1222 \
    protocol=tcp to-addresses=10.10.10.2 to-ports=22
add action=dst-nat chain=dstnat dst-address=157.119.186.253 dst-port=18080 \
    protocol=tcp to-addresses=10.10.10.2 to-ports=8080
add action=dst-nat chain=dstnat comment=Cacti dst-address=157.119.186.253 \
    dst-port=8083 protocol=tcp to-addresses=172.24.7.250 to-ports=80
/ip ipsec profile
set [ find default=yes ] dpd-interval=2m dpd-maximum-failures=5
/ip kid-control
add disabled=yes fri=0s-1d mon=0s-1d name=system-dummy sat=0s-1d sun=0s-1d \
    thu=0s-1d tue=0s-1d tur-fri=0s-1d tur-mon=0s-1d tur-sat=0s-1d tur-sun=\
    0s-1d tur-thu=0s-1d tur-tue=0s-1d tur-wed=0s-1d wed=0s-1d
/ip route
add comment="User NAT to CCR1009" disabled=yes distance=1 dst-address=\
    172.16.0.0/16 gateway=10.10.92.2 pref-src="" routing-table=main scope=30 \
    suppress-hw-offload=no target-scope=10
add comment="Easy Billing" disabled=yes distance=1 dst-address=10.10.90.0/30 \
    gateway=10.10.92.2 pref-src="" routing-table=main scope=30 \
    suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    43.245.142.192/27 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    43.245.142.224/27 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    103.15.244.128/27 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    103.15.246.96/27 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    103.15.244.160/27 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    103.242.216.64/27 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    103.242.216.96/27 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    103.242.216.128/27 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    144.48.148.192/27 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    144.48.148.224/27 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    157.119.187.0/27 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    103.15.244.96/27 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    103.242.216.160/27 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    157.119.187.64/27 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL FNA Node" disabled=no distance=1 dst-address=\
    43.245.142.128/26 gateway=103.199.86.81 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL FNA Node" disabled=no distance=1 dst-address=\
    103.242.216.0/26 gateway=103.199.86.81 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL FNA Node" disabled=no distance=1 dst-address=\
    103.242.218.128/26 gateway=103.199.86.81 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL FNA Node" disabled=no distance=1 dst-address=\
    103.242.216.192/26 gateway=103.199.86.81 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    103.96.70.0/28 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    103.96.70.64/26 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL FNA Node" disabled=no distance=1 dst-address=\
    103.165.159.0/26 gateway=103.199.86.81 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    157.119.187.32/27 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="User NAT to CCR1009" disabled=yes distance=1 dst-address=\
    192.168.255.0/30 gateway=10.10.92.2 pref-src="" routing-table=main scope=\
    30 suppress-hw-offload=no target-scope=10
add comment="User NAT to CCR1009" disabled=yes distance=1 dst-address=\
    10.10.20.0/24 gateway=10.10.92.2 pref-src="" routing-table=main scope=30 \
    suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    103.96.70.128/27 gateway=10.10.101.233 routing-table=main scope=30 \
    suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    103.96.70.160/27 gateway=10.10.101.233 routing-table=main scope=30 \
    suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    103.96.70.192/27 gateway=10.10.101.233 routing-table=main scope=30 \
    suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    103.96.70.224/27 gateway="" routing-table=main scope=30 \
    suppress-hw-offload=no target-scope=10
add comment=DNS disabled=no dst-address=172.30.8.0/21 gateway=10.20.7.254 \
    routing-table=main suppress-hw-offload=no
add comment="OLT IP" disabled=yes distance=1 dst-address=10.10.11.0/24 \
    gateway=10.10.92.2 pref-src="" routing-table=main scope=30 \
    suppress-hw-offload=no target-scope=10
add comment="SCL Default Route" disabled=no distance=2 dst-address=0.0.0.0/0 \
    gateway=157.119.186.254 pref-src="" routing-table=main scope=30 \
    suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    103.96.70.16/28 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add disabled=no dst-address=10.16.100.0/22 gateway=FTP routing-table=main \
    suppress-hw-offload=no
add disabled=no distance=1 dst-address=172.19.178.0/24 gateway=FTP \
    routing-table=main scope=30 suppress-hw-offload=no target-scope=10
add disabled=no distance=1 dst-address=172.16.29.0/24 gateway=FTP \
    routing-table=main scope=30 suppress-hw-offload=no target-scope=10
add disabled=no distance=1 dst-address=15.1.1.0/24 gateway=FTP routing-table=\
    main scope=30 suppress-hw-offload=no target-scope=10
add disabled=no distance=1 dst-address=15.1.2.0/24 gateway=FTP routing-table=\
    main scope=30 suppress-hw-offload=no target-scope=10
add disabled=no distance=1 dst-address=15.1.3.0/24 gateway=FTP routing-table=\
    main scope=30 suppress-hw-offload=no target-scope=10
add disabled=no distance=1 dst-address=15.1.4.0/24 gateway=FTP routing-table=\
    main scope=30 suppress-hw-offload=no target-scope=10
add disabled=no distance=1 dst-address=15.1.5.0/24 gateway=FTP routing-table=\
    main scope=30 suppress-hw-offload=no target-scope=10
add disabled=no distance=1 dst-address=15.2.2.0/24 gateway=FTP routing-table=\
    main scope=30 suppress-hw-offload=no target-scope=10
add disabled=no distance=1 dst-address=103.89.248.0/24 gateway=FTP \
    routing-table=main scope=30 suppress-hw-offload=no target-scope=10
add disabled=no distance=1 dst-address=154.95.93.0/24 gateway=FTP \
    routing-table=main scope=30 suppress-hw-offload=no target-scope=10
add disabled=no distance=1 dst-address=103.170.205.0/24 gateway=FTP \
    routing-table=main scope=30 suppress-hw-offload=no target-scope=10
add disabled=no distance=2 dst-address=114.130.116.0/22 gateway=\
    157.119.186.254 pref-src="" routing-table=main scope=30 \
    suppress-hw-offload=no target-scope=10
add comment="SCL Default Route" disabled=no distance=2 dst-address=\
    203.188.244.0/24 gateway=157.119.186.254 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    103.96.70.224/27 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    103.165.159.192/26 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="SCL Default Route" disabled=no distance=2 dst-address=\
    103.230.104.0/24 gateway=157.119.186.254 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    175.41.46.0/27 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
add comment="FC To SCL GGC Node" disabled=yes distance=1 dst-address=\
    103.165.159.192/26 gateway=10.10.101.233 pref-src="" routing-table=main \
    scope=30 suppress-hw-offload=no target-scope=10
/ip service
set telnet disabled=yes
set ftp disabled=yes
set www port=8091
set ssh port=8076
set api port=8067
set winbox port=8989
/ip smb shares
set [ find default=yes ] directory=/pub
/ip traffic-flow
set cache-entries=8M interfaces=\
    1.2254.INT,1.2255.GGC,1.2256.FNA,1.2258.SCL_CDN,1.2257.BDIX
/ip traffic-flow target
add disabled=yes dst-address=172.24.7.250
/ppp secret
add caller-id=80:AF:CA:8C:E1:92 comment="Md.Abdul Ahad" name=fc21 profile=\
    FCOM-1_5M service=pppoe
add caller-id=CC:2D:21:3A:ED:10 comment="Md.Ali Nawaj" name=fc28 profile=\
    FCOM-4_20M service=pppoe
add caller-id=D8:44:89:DE:A3:3D comment="Porimal Bonik" name=fc31 profile=\
    FCOM-1_5M service=pppoe
add caller-id=50:0F:F5:BA:95:A9 comment="Md.Mubarak Hossen" name=fc36 \
    profile=FCOM-2_8M remote-address=157.119.186.86 service=pppoe
add caller-id=BC:E0:01:49:44:6D comment="Sayed Shakir Ahmed / Md.Samim Miah" \
    name=fc23 profile=FCOM-2_8M service=pppoe
add caller-id=70:4F:57:E1:A4:31 comment="Terget Computer" name=fc52 profile=\
    FCOM-2_8M service=pppoe
add caller-id=D8:32:14:1F:65:48 comment="Abu Hannan Talukder" name=fc61 \
    profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:1E:86:80 comment="Mohammad Khayrul Islam" name=fc59 \
    profile=FCOM-1_5M service=pppoe
add caller-id=38:6B:1C:B2:3F:35 comment="Ringku Pal" name=fc71 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:10:DC:9F comment="Mst.Shapla Akther" name=fc81 \
    profile=FCOM-1_5M service=pppoe
add caller-id=38:6B:1C:B2:12:71 comment=Dentel.Atick name=fc87 profile=\
    FCOM-1_5M service=pppoe
add caller-id=38:6B:1C:B1:F1:37 comment="Ruyan Ahamed" name=fc86 profile=\
    FCOM-1_5M service=pppoe
add caller-id=38:6B:1C:B2:3E:0D comment="Md Azizul Huq" name=fc35 profile=\
    FCOM-2_8M service=pppoe
add caller-id=04:95:E6:58:5A:A0 comment=Rupu name=fc93 profile=FCOM-1_5M \
    service=pppoe
add caller-id=0C:80:63:78:66:35 comment="Khan Mohammad Hamja" name=fc94 \
    profile=FCOM-2_8M service=pppoe
add caller-id=BC:62:CE:CD:3D:14 comment="Mst.Chamly Akter Dokan" name=fc68 \
    profile=FCOM-1_5M service=pppoe
add caller-id=60:32:B1:09:BC:DB comment=Md.Kayes name=fc109 profile=FCOM-1_5M \
    service=pppoe
add caller-id=BC:62:CE:13:C6:88 comment="Sayed Reyad" name=fc11 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:1E:86:48 comment="Sipon Home" name=fc05 profile=\
    FCOM-1_5M service=pppoe
add caller-id=B4:0F:3B:8A:83:E8 comment="Fire Service" name=fc15 profile=\
    FCOM-4_20M service=pppoe
add caller-id=D8:32:14:73:3D:87 comment="Shak Khulil Rahaman" name=fc113 \
    profile=FCOM-2_8M service=pppoe
add caller-id=BC:62:CE:74:03:DF comment="Matri Jewelers" name=fc115 profile=\
    FCOM-3_12M service=pppoe
add caller-id=38:6B:1C:B2:14:85 comment=Jasim name=fc118 profile=FCOM-1_5M \
    service=pppoe
add caller-id=04:5E:A4:30:CA:B0 comment="Ashin Hojur" name=fc119 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:44:89:DE:7E:E1 comment=Md.Shahjahan name=fc125 profile=\
    FCOM-1_5M service=pppoe
add caller-id=CC:2D:21:E7:8C:30 comment="Md.Mahmudur Rahman" name=fc43 \
    profile=FCOM-3_12M service=pppoe
add caller-id=D8:32:14:1D:C8:98 comment="Md.Bappy Mia" name=fc13 profile=\
    FCOM-2_8M service=pppoe
add caller-id=BC:62:CE:16:CE:5F comment="Rajib Pal" name=fc79 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:1F:66:48 comment="Sarwar Jahan Mukul" name=fc41 \
    profile=FCOM-3_12M service=pppoe
add caller-id=08:55:31:25:5A:53 comment="Walton Plaza" name=Madan profile=\
    FCOM-4_20M service=pppoe
add caller-id=04:5E:A4:E9:5F:99 comment="Raju Baisya" name=fc50 profile=\
    FCOM-3_12M service=pppoe
add caller-id=04:5E:A4:EA:23:34 comment="Uzzal Mia (Home)" name=fc42 profile=\
    FCOM-2_8M service=pppoe
add caller-id=38:6B:1C:B1:F5:35 comment="A K M Fazlur Rob" name=fc44 profile=\
    FCOM-3_12M service=pppoe
add caller-id=D8:32:14:1E:67:E8 comment="Md.Sirajul Islam" name=fc97 profile=\
    FCOM-1_5M service=pppoe
add caller-id=90:9A:4A:6C:19:FE comment="Prosanto Computer" name=fc120 \
    profile=FCOM-4_20M service=pppoe
add caller-id=BC:62:CE:A9:85:B3 comment="Ripon Roy" name=ripon1 profile=\
    FCOM-1_5M service=pppoe
add caller-id=08:40:F3:90:46:20 comment="Anik Dab Nath" name=fc49 profile=\
    FCOM-3_12M service=pppoe
add comment="Joy Durga Jewelers" name=fc17 profile=FCOM-4_20M service=pppoe
add caller-id=B0:A7:B9:30:CD:87 comment="Mst.Jakia Akter" name=fc63 profile=\
    FCOM-1_5M service=pppoe
add caller-id=60:32:B1:AB:B5:6B comment="Sayed Bilas Gark" name=fc73 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:1E:94:B0 comment="Samir Bhuson Baisya HOME" name=fc121 \
    profile=FCOM-2_8M service=pppoe
add caller-id=84:D8:1B:92:94:7B comment="Tapos Chowdhury" name=fc126 profile=\
    FCOM-1_5M service=pppoe
add caller-id=EC:75:0C:ED:01:D3 comment="Rahmim Mia" name=fc37 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:07:2C:31 comment=Md.Shohag name=fc02 profile=FCOM-2_8M \
    service=pppoe
add caller-id=D8:32:14:70:A4:C9 comment="Bazlu Mia" name=fc67 profile=\
    FCOM-2_8M service=pppoe
add caller-id=D8:47:32:3C:A6:2F comment="Sheikh Momen" name=fc133 profile=\
    FCOM-2_8M service=pppoe
add caller-id=DC:8E:8D:51:D7:36 comment="Mehedhi Hasan" name=fc136 profile=\
    FCOM-4_20M service=pppoe
add caller-id=04:5E:A4:C5:24:57 comment="Students Parliament Batch 2002" \
    name=fc131 profile=FCOM-2_8M service=pppoe
add comment="Muhan Malakar" name=fc22 profile=FCOM-2_8M service=pppoe
add caller-id=DC:8E:8D:86:56:86 comment="Shishir Jabin" name=fc127 profile=\
    FCOM-1_5M service=pppoe
add caller-id=B8:3A:08:FE:9C:90 comment="Delip Chandra Das" name=fc89 \
    profile=FCOM-4_20M service=pppoe
add caller-id=74:DA:88:63:93:7B comment="Ripon Roy.Home" name=ripon3 profile=\
    FCOM-1_5M service=pppoe
add caller-id=E8:48:B8:BE:0A:71 comment="Nasim Khan" name=fc108 profile=\
    FCOM-2_8M service=pppoe
add caller-id=04:5E:A4:30:5A:3A comment="Dipta Kumar Pal" name=fc30 profile=\
    FCOM-2_8M service=pppoe
add caller-id=98:C7:A4:2B:CD:5B comment="Basonti Rani Bonik" name=fc55 \
    profile=FCOM-1_5M service=pppoe
add caller-id=04:5E:A4:F3:8C:C3 comment="Myful Khanam" name=fc57 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:62:CE:13:C6:38 comment=Monai name=fc98 profile=FCOM-1_5M \
    service=pppoe
add caller-id=BC:62:CE:80:DF:34 comment="Md.Rabbul Mia" name=fc72 profile=\
    FCOM-2_8M service=pppoe
add caller-id=04:5E:A4:F3:8D:22 comment="Helim Khan" name=fc140 profile=\
    FCOM-1_5M service=pppoe
add caller-id=04:95:E6:58:3E:E8 comment="Rapiq Lily Video" name=fc75 profile=\
    FCOM-5_25M service=pppoe
add caller-id=00:31:92:AE:79:99 comment="LGED Madan" name=fc95 profile=\
    FCOM-2_8M service=pppoe
add caller-id=BC:62:CE:4D:10:44 comment="Ujjal Chandra Sarker" name=fc40 \
    profile=FCOM-2_8M service=pppoe
add caller-id=BC:62:CE:00:27:F4 comment="UPAZILA NIRBAHI OFFICERS" name=fc53 \
    profile=FCOM-5_25M service=pppoe
add caller-id=BC:62:CE:80:DF:6B comment="Md.Shahjahan Home" name=fc84 \
    profile=FCOM-2_8M service=pppoe
add caller-id=D8:32:14:1E:63:98 comment="Sharif Shah" name=fc26 profile=\
    FCOM-2_8M service=pppoe
add caller-id=10:27:F5:37:8B:D7 comment="Md: Din Islam" name=fc143 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:57:CB:E7 comment="Anik day" name=fc16 profile=\
    FCOM-1_5M service=pppoe
add caller-id=38:6B:1C:B2:0F:7F comment="Shemol Dey" name=fc69 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:07:1D:C9:57:FA comment="Maruf Islam" name=fc146 profile=\
    FCOM-2_8M service=pppoe
add caller-id=6C:5A:B0:01:10:F4 comment=Dipak name=fc07 profile=FCOM-1_5M \
    service=pppoe
add caller-id=04:95:E6:0F:8B:10 comment="DR NOYON" name=fc100 profile=\
    FCOM-1_5M service=pppoe
add caller-id=B4:B0:24:82:8F:15 comment="Md.Hasanur Rashid Khan" name=fc150 \
    profile=FCOM-1_5M service=pppoe
add caller-id=AC:15:A2:B0:82:9D comment="Md.Shahanur Rahman Talukder" name=\
    fctest profile=FCOM-2_8M service=pppoe
add caller-id=50:0F:F5:AB:07:50 comment="Chowdhury Humayan Kabir" name=fc64 \
    profile=FCOM-2_8M service=pppoe
add caller-id=94:46:96:E1:9D:60 comment="Rangdhanu Hotel" name=fc80 profile=\
    FCOM-3_12M service=pppoe
add caller-id=18:D6:C7:84:55:ED comment="Rana Talokder" name=fc106 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:1E:D4:98 comment=Mst:Halima name=fc145 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:1E:85:00 comment="Pancham Das" name=fc58 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:BB:D8:5A comment=Shamim name=fc10 profile=FCOM-1_5M \
    service=pppoe
add caller-id=50:0F:F5:82:65:3F comment="Rojina Akther" name=fc111 profile=\
    FCOM-3_12M service=pppoe
add caller-id=BC:E0:01:49:32:2A comment="Agricultural Office" name=fc123 \
    profile=FCOM-3_12M service=pppoe
add caller-id=BC:62:CE:13:C6:42 comment="Md.Furkanur Rasid Khan" name=fc149 \
    profile=FCOM-4_20M service=pppoe
add caller-id=B0:A7:B9:30:D1:C1 comment="UPAZILA Tiunu Home" name=fc101 \
    profile=FCOM-5_25M service=pppoe
add caller-id=B4:B0:24:07:12:41 comment="Mst:Suiti Akter" name=fc147 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:62:CE:1B:9F:8D comment=Saddam name=fc01 profile=FCOM-3_12M \
    service=pppoe
add caller-id=58:D9:D5:D5:41:E8 comment="Md.Usama Talukder Hridoy" name=fc152 \
    profile=FCOM-2_8M service=pppoe
add caller-id=BC:62:CE:1B:9F:86 comment="Sushil Chandra Pal" name=fc78 \
    profile=FCOM-1_5M service=pppoe
add comment="MIRJA RAJON MIA" name=fc24 profile=FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:07:62:C8 comment="Bappy Paul" name=fc88 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:1E:85:50 comment="Amirul Islam" name=fc104 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:62:CE:00:78:0B comment="Giash Mahmud" name=fc134 profile=\
    FCOM-3_12M service=pppoe
add caller-id=04:95:E6:C3:DB:17 comment=Md.Motaleb name=fc107 profile=\
    FCOM-3_12M service=pppoe
add caller-id=BC:62:CE:C5:4E:8F comment=Md.Alam name=fc103 profile=FCOM-1_5M \
    service=pppoe
add caller-id=D8:32:14:4E:07:B8 comment="Shilina Akter" name=fc154 profile=\
    FCOM-2_8M service=pppoe
add caller-id=38:6B:1C:B2:19:E7 comment="Mrs.Jorna Chowdury" name=fc155 \
    profile=FCOM-1_5M service=pppoe
add caller-id=BC:62:CE:CD:2D:6F comment=Md:Alamin name=fc159 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:62:CE:BC:64:34 comment="Md.Faruk Ahmmed" name=fc161 profile=\
    FCOM-2_8M service=pppoe
add caller-id=BC:E0:01:87:32:8E comment="Sajjad kobir thoshar" name=fc162 \
    profile=FCOM-1_5M service=pppoe
add caller-id=34:60:F9:D6:0B:37 comment="Md.Saddam Hossain" name=fc164 \
    profile=FCOM-2_8M service=pppoe
add caller-id=34:60:F9:D6:0A:21 comment="Israhil Shekh" name=fc169 profile=\
    FCOM-2_8M service=pppoe
add caller-id=50:3D:D1:3B:F9:D1 comment="Badruzzaman Mamun" name=fc171 \
    profile=FCOM-2_8M service=pppoe
add caller-id=BC:E0:01:49:45:12 comment="Aminul Islam" name=fc70 profile=\
    FCOM-2_8M service=pppoe
add caller-id=D8:32:14:71:FE:17 comment=Robin name=fc172 profile=FCOM-2_8M \
    service=pppoe
add caller-id=04:5E:A4:F7:7F:73 comment="Shakh Uzzal Ahmmed" name=fc173 \
    profile=FCOM-3_12M service=pppoe
add caller-id=34:60:F9:D6:0A:E7 comment="Madan Somobay office" name=fc174 \
    profile=FCOM-2_8M service=pppoe
add caller-id=BC:62:CE:E1:04:EF comment="Asa Office" name=fc177 profile=\
    FCOM-2_8M service=pppoe
add caller-id=14:EB:B6:A4:65:4F comment="Upazila Fisheries Office" name=fc178 \
    profile=FCOM-3_12M service=pppoe
add caller-id=BC:E0:01:07:6B:E7 name=rupon profile=FCOM-3_12M service=pppoe
add caller-id=BC:E0:01:10:27:F8 comment="Arman Mia" name=fc183 profile=\
    FCOM-2_8M service=pppoe
add caller-id=50:0F:F5:06:F5:4F comment="Md.Narul Huqe" name=fc184 profile=\
    FCOM-2_8M service=pppoe
add caller-id=D8:32:14:6B:30:78 comment="Diphok Sarker" name=fc185 profile=\
    FCOM-2_8M service=pppoe
add caller-id=CC:2D:21:73:BB:87 comment="Saiful Islam Tuton" name=fc186 \
    profile=FCOM-2_8M service=pppoe
add caller-id=50:D4:F7:C8:53:A3 comment=Prantro name=fc188 profile=FCOM-1_5M \
    service=pppoe
add caller-id=BC:E0:01:10:A0:B6 comment="Md.Shahidul Islam" name=fc189 \
    profile=FCOM-1_5M service=pppoe
add caller-id=E0:1C:FC:43:F5:9B comment="Md.Asraful Hoda Chowdury" name=fc193 \
    profile=FCOM-3_12M service=pppoe
add caller-id=DC:8E:8D:5C:F8:33 comment="Laki Akter" name=fc192 profile=\
    FCOM-2_8M service=pppoe
add caller-id=54:AF:97:56:EF:4D comment="Apel Bai / Tajmahal Moni" name=fc85 \
    profile=FCOM-1_5M service=pppoe
add caller-id=08:40:F3:90:0F:18 comment="Bishas Bosraloy" name=fc168 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:07:6A:61 comment="Mst.Rojy Akter" name=fc197 profile=\
    FCOM-2_8M service=pppoe
add caller-id=C0:06:C3:CE:E1:0B comment="Md.Safayet Hossin" name=fc198 \
    profile=FCOM-2_8M service=pppoe
add caller-id=34:60:F9:D6:43:41 comment="Seteara Akter Fateha" name=fc199 \
    profile=FCOM-1_5M service=pppoe
add caller-id=38:6B:1C:B2:3E:77 comment="Mamun {komishonar}" name=fc200 \
    profile=FCOM-1_5M service=pppoe
add caller-id=7C:8B:CA:82:DD:5B comment="Md.Mijanur Rahman Parvej" name=fc201 \
    profile=FCOM-1_5M service=pppoe
add caller-id=D4:6E:0E:4C:48:B9 comment="Apu Paul" name=fc202 profile=\
    FCOM-2_8M service=pppoe
add caller-id=BC:E0:01:07:2C:B3 comment=Kayes name=kayes profile=FCOM-6_30M \
    service=pppoe
add caller-id=D4:31:27:05:A3:5C comment="RAKAL/ BAI BAI SAIKEL" name=fc205 \
    profile=FCOM-1_5M service=pppoe
add caller-id=BC:62:CE:4D:58:C4 comment="Pronob Bisho Sharma" name=fc206 \
    profile=FCOM-1_5M service=pppoe
add caller-id=E0:1C:FC:3F:32:83 comment="Upazila Election Office" name=fc209 \
    profile=FCOM-4_20M remote-address=157.119.186.84 service=pppoe
add caller-id=BC:E0:01:10:3B:8A comment="Mst.Chamly Akter Home" name=fc210 \
    profile=FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:87:EA:08 comment=\
    "Directorate of Public Health Engineering" name=fc191 profile=FCOM-2_8M \
    service=pppoe
add caller-id=34:60:F9:6C:CB:81 comment="P I O Madan" name=fc211 profile=\
    FCOM-5_25M service=pppoe
add caller-id=BC:E0:01:49:62:04 comment="Robin Home" name=fc213 profile=\
    FCOM-1_5M service=pppoe
add caller-id=04:95:E6:95:2A:10 comment="Riad Akand" name=fc214 profile=\
    FCOM-3_12M service=pppoe
add caller-id=04:5E:A4:EA:23:DE comment="Ma Cosmetic" name=fc215 profile=\
    FCOM-2_8M service=pppoe
add caller-id=98:DE:D0:EA:41:61 comment="\tRipon Pashtik" name=fc19 profile=\
    FCOM-1_5M service=pppoe
add caller-id=38:6B:1C:B1:F3:C7 comment="Md.Sahab Uddin" name=fc117 profile=\
    FCOM-3_12M service=pppoe
add caller-id=BC:E0:01:49:9A:4E comment="Ringku Pal" name=fc135 profile=\
    FCOM-1_5M service=pppoe
add caller-id=CC:2D:21:E7:E2:98 comment="SHUV AHMMOD" name=fc38 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:49:1E:93 comment="Uttam Pal Home" name=fc167 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:62:CE:15:46:43 comment="Joy Chandra" name=fc216 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:49:45:1C comment="Niloy Telecom" name=fc220 profile=\
    FCOM-2_8M service=pppoe
add caller-id=B8:3A:08:67:4D:38 comment="Uttam Pal Dokan" name=fc221 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:10:83:51 comment="Johirul Islam" name=fc222 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:62:CE:4D:58:6A comment="Khadija Aktar" name=fc227 profile=\
    FCOM-2_8M service=pppoe
add caller-id=58:D9:D5:9E:E0:60 comment="Shakib Hashan" name=fc153 profile=\
    FCOM-1_5M service=pppoe
add caller-id=24:2F:D0:0A:75:39 comment="Md. Lokman Hoshen" name=fc163 \
    profile=FCOM-3_12M service=pppoe
add caller-id=BC:E0:01:10:B4:02 comment="Sochedro Sarker" name=fc116 profile=\
    FCOM-1_5M service=pppoe
add caller-id=58:D9:D5:35:57:10 comment="Md Mubarak Hosen" name=fc229 \
    profile=FCOM-3_12M service=pppoe
add comment=MD.Rofiq name=fc230 profile=FCOM-2_8M service=pppoe
add caller-id=C0:A4:76:30:35:A1 comment="Md.Milon Mia" name=fc231 profile=\
    FCOM-2_8M service=pppoe
add caller-id=50:0F:F5:BD:51:CF comment=POLIN name=fc233 profile=FCOM-1_5M \
    service=pppoe
add caller-id=BC:E0:01:10:A0:FC comment="Ashin Hojur" name=fc204 profile=\
    FCOM-1_5M service=pppoe
add caller-id=B4:B0:24:57:E7:34 comment="Monwara Akter" name=fc239 profile=\
    FCOM-3_12M service=pppoe
add caller-id=50:0F:F5:B2:77:A0 comment="Ankon Kumar Mitra" name=fc240 \
    profile=FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:10:A1:42 comment="Modon College" name=fc234 profile=\
    FCOM-4_20M service=pppoe
add caller-id=D8:32:14:69:84:40 comment=RIMU name=fc241 profile=FCOM-1_5M \
    service=pppoe
add caller-id=BC:E0:01:10:83:5B comment="Robiqul Islam" name=fc139 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:10:A0:D4 comment="Md.Zahangir Alam" name=fc137 \
    profile=FCOM-1_5M service=pppoe
add caller-id=04:5E:A4:F3:8C:FA comment="Md.Oliullah Babul" name=fc243 \
    profile=FCOM-1_5M service=pppoe
add caller-id=40:ED:00:32:6E:7D comment="Mst.Fahmida Sultana Lucky" name=\
    fc245 profile=FCOM-1_5M service=pppoe
add caller-id=58:D9:D5:68:C7:68 comment="Rahmim Mia" name=fc246 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:62:CE:6F:3C:1B comment="Pinke Rani Paul" name=fc249 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:13:98:B7 comment=Zinath name=fc203 profile=FCOM-2_8M \
    service=pppoe
add caller-id=50:0F:F5:AA:70:10 comment=S.A.Al-Amin name=fc251 profile=\
    FCOM-1_5M service=pppoe
add caller-id=58:D9:D5:19:97:60 comment=Manik name=fc253 profile=FCOM-2_8M \
    service=pppoe
add caller-id=40:ED:00:32:6E:B9 comment="Ali Azam Khan" name=fc254 profile=\
    FCOM-1_5M service=pppoe
add caller-id=38:6B:1C:B1:F3:B9 comment="Rakib(Shanto)" name=fc45 profile=\
    FCOM-1_5M service=pppoe
add caller-id=A8:29:48:6D:A7:6B comment="Ali Hasan" name=fc256 profile=\
    FCOM-2_8M service=pppoe
add name=foysal profile=FCOM-1_5M service=pptp
add caller-id=DC:8E:8D:6A:60:36 comment="Md.mizanur Rahaman Robin" name=fc259 \
    profile=FCOM-2_8M service=pppoe
add caller-id=BC:62:CE:CD:53:9E comment="Talokder Fashan" name=fc260 profile=\
    FCOM-2_8M service=pppoe
add caller-id=24:2F:D0:0A:64:0F comment="Shaukoth Islam" name=fc261 profile=\
    FCOM-2_8M service=pppoe
add caller-id=BC:E0:01:87:6D:7B comment="MD Rabiullah" name=fc262 profile=\
    FCOM-1_5M service=pppoe
add caller-id=60:32:B1:18:CE:04 comment="MD Abu Sadek Mia" name=fc263 \
    profile=FCOM-3_12M service=pppoe
add caller-id=50:D4:F7:E3:FC:19 comment=Jesmin name=fc264 profile=FCOM-1_5M \
    service=pppoe
add caller-id=40:ED:00:74:51:61 comment="Samia Akter Chowdhury" name=fc265 \
    profile=FCOM-3_12M service=pppoe
add caller-id=DC:8E:8D:4F:BD:EF comment=Sohel name=fc266 profile=FCOM-2_8M \
    service=pppoe
add caller-id=D8:32:14:57:58:87 comment="Chamsul Islam" name=fc267 profile=\
    FCOM-1_5M service=pppoe
add caller-id=30:68:93:D2:E4:73 comment="SUBORNA AKTER" name=fc268 profile=\
    FCOM-3_12M service=pppoe
add caller-id=58:D9:D5:9E:92:70 comment=Halim name=fc273 profile=FCOM-1_5M \
    service=pppoe
add caller-id=BC:E0:01:34:43:BF comment="Humayun Kabir" name=fc275 profile=\
    FCOM-1_5M service=pppoe
add caller-id=5C:E9:31:B3:F8:CA comment="Ashif Ibn Islam" name=fc276 profile=\
    FCOM-3_12M service=pppoe
add caller-id=5C:E9:31:5A:22:35 comment="ANOWER MIA" name=fc279 profile=\
    FCOM-3_12M service=pppoe
add caller-id=90:61:0C:A2:9F:05 comment="Md.Uday / ABIR" name=fc280 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:A5:FD:F8 comment="JAHANARA AKTHER KHATUN" name=fc281 \
    profile=FCOM-1_5M service=pppoe
add caller-id=BC:62:CE:00:28:09 comment=KUHINUR name=fc282 profile=FCOM-1_5M \
    service=pppoe
add caller-id=50:0F:F5:AA:16:88 comment=Mijan name=fc283 profile=FCOM-2_8M \
    service=pppoe
add caller-id=98:C7:A4:2B:CC:1F comment="Saiful Islam" name=fc286 profile=\
    FCOM-2_8M service=pppoe
add caller-id=98:C7:A4:2B:C4:A7 comment=Bablu name=fc288 profile=FCOM-4_20M \
    service=pppoe
add caller-id=98:C7:A4:2B:CC:27 comment="Ma Cosmetic Home" name=fc289 \
    profile=FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:9D:FA:26 comment="NAMITA PAUL" name=fc291 profile=\
    FCOM-3_12M service=pppoe
add caller-id=5C:62:8B:66:75:27 comment="MD SHIKUL ISLAM" name=fc293 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:07:B6:E7:DC:0D comment="Md.Shahiahan Kabir" name=fc295 \
    profile=FCOM-3_12M service=pppoe
add caller-id=BC:62:CE:DD:C2:70 comment="Girl's School" name=fc179 profile=\
    FCOM-5_25M service=pppoe
add caller-id=BC:E0:01:8A:23:58 comment="Kamrul Hasan" name=fc219 profile=\
    FCOM-1_5M service=pppoe
add caller-id=40:ED:00:62:21:C3 comment="RATNA AKTHER" name=fc297 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:07:2F:79 comment=Parag name=fc299 profile=FCOM-1_5M \
    service=pppoe
add caller-id=B8:3A:08:67:4D:30 comment="Laki Akter" name=fc300 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:62:CE:CD:55:F1 comment="RATON CHANDRO DAS" name=fc301 \
    profile=FCOM-3_12M service=pppoe
add caller-id=58:D9:D5:6A:68:40 comment="SAIKOT JHAN AKASH" name=fc303 \
    profile=FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:9F:B3:CE comment=BALAYET name=fc304 profile=FCOM-3_12M \
    service=pppoe
add caller-id=CC:2D:21:17:4F:C0 comment="Sharif Shah Home" name=fc305 \
    profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:57:2F:C7 comment=ARADON name=fc308 profile=FCOM-1_5M \
    service=pppoe
add comment="TITU CHOWDHURY" name=fc56 profile=FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:AA:94:B6 comment="SAYLA AKTER" name=fc156 profile=\
    FCOM-1_5M service=pppoe
add caller-id=60:A4:B7:F0:4E:25 comment="Md.Robiul Islam Rony" name=fc14 \
    profile=FCOM-3_12M service=pppoe
add caller-id=9C:9D:7E:8C:1C:29 comment="ROJON CHANDRA ROY" name=fc315 \
    profile=FCOM-2_8M service=pppoe
add caller-id=5C:A6:E6:4A:3C:59 comment="TASLIMA AKTER" name=fc317 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:87:39:33 comment="Social Service Office" name=fc319 \
    profile=FCOM-1_5M service=pppoe
add caller-id=00:EB:D8:78:8D:F3 comment="TRISNA AKTER" name=fc320 profile=\
    FCOM-1_5M service=pppoe
add caller-id=B0:A7:B9:5C:60:39 comment="Rural Electricity Office" name=fc105 \
    profile=FCOM-4_20M service=pppoe
add caller-id=DC:8E:8D:25:60:0A comment="Bhupesh Kumar Chowdhury" name=fc51 \
    profile=FCOM-2_8M service=pppoe
add caller-id=D8:32:14:13:98:BF comment="Tofayl Mia" name=fc252 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:A5:3C:77 comment="LOKMAN HEKIM" name=fc20 profile=\
    FCOM-1_5M service=pppoe
add caller-id=3C:52:A1:AE:88:47 comment="ANTU ROY" name=fc272 profile=\
    FCOM-2_8M service=pppoe
add caller-id=BC:E0:01:07:17:FA comment="MD ABUL HASANATH" name=fc325 \
    profile=FCOM-2_8M service=pppoe
add caller-id=D8:32:14:77:06:F0 comment="Papia Akter" name=fc326 profile=\
    FCOM-1_5M service=pppoe
add caller-id=50:0F:F5:B2:1F:50 comment="SAIFUL ISLAM" name=fc327 profile=\
    FCOM-1_5M service=pppoe
add caller-id=50:D4:F7:0B:8A:29 comment="AKASH MIA" name=fc328 profile=\
    FCOM-1_5M service=pppoe
add caller-id=C8:E7:D8:98:D6:69 comment="JAMAL UDDIN" name=fc332 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:1F:67:40 comment="SHOHEL CHANDRA" name=fc334 profile=\
    FCOM-1_5M service=pppoe
add caller-id=04:95:E6:95:2A:18 comment=Nahar name=fc335 profile=FCOM-1_5M \
    service=pppoe
add caller-id=BC:E0:01:87:39:FE comment="ABDUL MONNAS MIR" name=fc292 \
    profile=FCOM-2_8M service=pppoe
add caller-id=D8:32:14:77:47:A0 comment="Sub Ragistry Office" name=fc336 \
    profile=FCOM-1_5M service=pppoe
add caller-id=E8:48:B8:7C:5B:45 comment="Pieyas Das" name=fc338 profile=\
    FCOM-2_8M service=pppoe
add caller-id=04:95:E6:95:2A:20 comment="Habiba Akter Chodhury" name=fc340 \
    profile=FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:CD:DC:2D comment="Salam Master" name=fc343 profile=\
    FCOM-1_5M service=pppoe
add caller-id=94:46:96:A6:BC:3D comment="Mitu Nag" name=fc344 profile=\
    FCOM-2_8M service=pppoe
add caller-id=BC:E0:01:E4:03:5C comment="Atik Mia" name=fc342 profile=\
    FCOM-1_5M service=pppoe
add caller-id=04:95:E6:95:2A:00 comment="MD SHAFIKUL ISLAM TAREK" name=fc346 \
    profile=FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:87:4A:20 comment=MAHFUG name=fc347 profile=FCOM-3_12M \
    service=pppoe
add comment="Arif Home" name=fc348 profile=FCOM-4_20M service=pppoe
add caller-id=64:EE:B7:48:0F:BA comment=Toufik name=fc350 profile=FCOM-1_5M \
    service=pppoe
add caller-id=04:95:E6:95:29:E8 comment="BIKASH CHANDRA DAS" name=fc351 \
    profile=FCOM-1_5M service=pppoe
add caller-id=24:2F:D0:0A:80:B5 comment="Haji Birani 2" name=fc352 profile=\
    FCOM-1_5M service=pppoe
add comment="KOBIR AHMED" name=fc354 profile=FCOM-2_8M service=pppoe
add caller-id=BC:E0:01:E1:0B:48 comment="MD ALINUR /GUNI MIA" name=fc355 \
    profile=FCOM-1_5M service=pppoe
add caller-id=CC:2D:21:71:AB:3F comment="JAMRUL MIA" name=fc356 profile=\
    FCOM-2_8M service=pppoe
add caller-id=BC:E0:01:87:3A:21 comment="ROBIUL /SHAHIN MIA" name=fc357 \
    profile=FCOM-2_8M service=pppoe
add caller-id=50:3D:D1:3B:F9:99 comment="Ripon Roy(Electrical)" name=ripon2 \
    profile=FCOM-1_5M service=pppoe
add caller-id=50:0F:F5:29:C4:89 comment="HM Shafiul Alam Homayon HOME" name=\
    fc359 profile=FCOM-1_5M service=pppoe
add caller-id=CC:2D:21:9E:C9:3F comment="ABDUS SAMAD LIJON" name=fc362 \
    profile=FCOM-3_12M service=pppoe
add caller-id=CC:2D:21:5A:49:8F comment="Julhas Uddin" name=fc364 profile=\
    FCOM-1_5M service=pppoe
add caller-id=CC:2D:21:9E:C9:47 comment="Atikul Islam Putul" name=fc366 \
    profile=FCOM-2_8M service=pppoe
add caller-id=60:A4:B7:E3:72:8D comment="Singki Akther" name=fc369 profile=\
    FCOM-2_8M service=pppoe
add caller-id=3C:CD:57:70:44:52 comment="Rakib Mia / Tarek Mia" name=fc370 \
    profile=FCOM-1_5M service=pppoe
add caller-id=DC:8E:8D:01:E7:2C comment="Sakhawath Sossain" name=fc371 \
    profile=FCOM-2_8M service=pppoe
add caller-id=58:D9:D5:81:EB:28 comment="Sharabani Akther" name=fc372 \
    profile=FCOM-3_12M service=pppoe
add caller-id=D8:32:14:1F:66:60 comment="Mazharul Alam / Sakib" name=fc373 \
    profile=FCOM-3_12M service=pppoe
add caller-id=CC:2D:21:73:BB:B7 comment="Sujit Kormokar" name=fc374 profile=\
    FCOM-1_5M service=pppoe
add caller-id=98:25:4A:85:43:23 comment="Idrissia Pre-Cadet School" name=\
    fc376 profile=FCOM-2_8M service=pppoe
add caller-id=A8:29:48:6D:75:37 comment=Zarida name=fc377 profile=FCOM-3_12M \
    service=pppoe
add caller-id=94:46:96:F3:1F:32 comment="Parvin Akher" name=fc378 profile=\
    FCOM-1_5M service=pppoe
add caller-id=CC:2D:21:E7:E2:D8 comment="Alpina Akter / Ripon mia" name=fc379 \
    profile=FCOM-1_5M service=pppoe
add caller-id=3C:6A:D2:16:19:BD comment="Mahmudul Hasan Dipu" name=fc380 \
    profile=FCOM-2_8M service=pppoe
add caller-id=D8:32:14:FD:FC:60 comment="Kamrul Hosen / lingkon" name=fc381 \
    profile=FCOM-2_8M service=pppoe
add caller-id=84:16:F9:AD:48:37 comment="Shahinur Akter" name=fc382 profile=\
    FCOM-1_5M service=pppoe
add caller-id=CC:2D:21:73:BB:8F comment=Mamun name=fc383 profile=FCOM-2_8M \
    service=pppoe
add caller-id=50:0F:F5:F0:D3:CF comment=Rupu name=fc385 profile=FCOM-1_5M \
    service=pppoe
add caller-id=CC:2D:21:73:BB:CF comment="Pavel Baishya" name=fc387 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:57:2F:CF comment="Saim Mia" name=fc388 profile=\
    FCOM-3_12M service=pppoe
add comment="BILLAL UDDIN" name=fc391 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:12:F8:07 comment="Amol Chandra Sarker" name=fc393 \
    profile=FCOM-3_12M service=pppoe
add caller-id=D8:32:14:12:F7:FF comment="ANISUR RAHMAN" name=fc394 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:41:86:17 comment="Md Abul Kalam Azad" name=fc397 \
    profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:4B:C1:CF comment="Antor Kar" name=fc398 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:E3:57:B9 comment="Muhammad Imam Hossen" name=fc399 \
    profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:4B:C1:C7 comment="Amjad Mia" name=fc401 profile=\
    FCOM-3_12M service=pppoe
add caller-id=B8:3A:08:7F:86:07 comment="Sopon chandra das" name=fc402 \
    profile=FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:3B:96:E9 comment="Tusa Ranjan" name=fc404 profile=\
    FCOM-1_5M service=pppoe
add caller-id=38:6B:1C:B1:F4:FD comment="Ministry Of Livestock" name=fc407 \
    profile=FCOM-1_5M service=pppoe
add caller-id=90:61:0C:82:C5:4D comment="Shahana Akter " name=fc114 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:3D:77:10 comment="Arif Fisary" name=fc411 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:56:9C:47 comment="Mohiuddin Market Bonik Somiti" name=\
    fc413 profile=FCOM-1_5M service=pppoe
add comment=Nibir name=fc414 profile=FCOM-4_20M service=pppoe
add caller-id=B8:3A:08:40:0F:EF comment="Jaya Shimux /Manna" name=fc415 \
    profile=FCOM-1_5M service=pppoe
add caller-id=DC:8E:8D:26:A0:19 comment="Achia Akter" name=fc418 profile=\
    FCOM-1_5M service=pppoe
add caller-id=E4:FA:C4:81:E7:A9 comment="Popy Office" name=fc420 profile=\
    FCOM-3_12M service=pppoe
add caller-id=DC:8E:8D:3A:D8:8A comment="Md Shamsul Haque Phatan" name=fc421 \
    profile=FCOM-1_5M service=pppoe
add caller-id=B8:3A:08:40:0F:B7 comment="Prollad Chandro Sarker" name=fc422 \
    profile=FCOM-2_8M service=pppoe
add comment="Bimol Chandro Sarker" name=fc423 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:1E:B0:20 comment="Shakh Bodrul Islam" name=fc428 \
    profile=FCOM-4_20M service=pppoe
add caller-id=E4:FA:C4:F3:E2:23 comment="Orup Talukder" name=fc433 profile=\
    FCOM-1_5M service=pppoe
add caller-id=60:32:B1:B4:37:7E comment="Juniad Mahmud[Mamun]" name=fc434 \
    profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:F3:B6:A8 comment=Imran name=fc435 profile=FCOM-1_5M \
    service=pppoe
add caller-id=B8:3A:08:40:0F:E7 comment="Afruza Akter Ritu" name=fc437 \
    profile=FCOM-1_5M service=pppoe
add caller-id=B8:3A:08:40:0F:D7 comment="Druvo Sarker" name=fc438 profile=\
    FCOM-3_12M service=pppoe
add caller-id=BC:E0:01:9F:B3:9D comment="Rofik [Chiyarman]" name=fc440 \
    profile=FCOM-1_5M service=pppoe
add caller-id=80:3F:5D:63:4F:C9 comment="Oliullah Naim" name=fc443 profile=\
    FCOM-1_5M service=pppoe
add caller-id=B8:3A:08:63:88:18 comment="Alam Tredas" name=fc445 profile=\
    FCOM-2_8M service=pppoe
add caller-id=40:AE:30:B0:B8:97 comment="Polash Mia" name=fc406 profile=\
    FCOM-1_5M service=pppoe
add caller-id=98:BA:5F:A8:BC:1D comment="Md Anoyar Jahid" name=fc446 profile=\
    FCOM-1_5M service=pppoe
add caller-id=9C:A2:F4:B2:1C:3C comment="Tapon Das" name=fc448 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:D7:8C:68 comment="Md Sohanur Rahman" name=fc449 \
    profile=FCOM-1_5M service=pppoe
add caller-id=74:FE:CE:F9:B2:CD comment="Md Shariful Haq" name=fc450 profile=\
    FCOM-2_8M service=pppoe
add caller-id=B8:3A:08:63:87:F8 comment="Kaji Abu Saim" name=fc451 profile=\
    FCOM-2_8M service=pppoe
add caller-id=DC:8E:8D:25:EF:4B comment="S M Oliulla" name=fc453 profile=\
    FCOM-1_5M service=pppoe
add caller-id=40:AE:30:B0:B8:65 comment=Rajan name=fc454 profile=FCOM-1_5M \
    service=pppoe
add caller-id=D8:32:14:69:84:00 comment=Tumpa name=fc463 profile=FCOM-2_8M \
    service=pppoe
add caller-id=C4:E9:84:22:9B:7B comment="Magribul Rahman {baccu}" name=fc465 \
    profile=FCOM-2_8M service=pppoe
add caller-id=60:83:E7:69:79:43 comment="\tMD Inchan Mia Master" name=fc466 \
    profile=FCOM-3_12M service=pppoe
add comment="Friends Communications" name=office profile=FCOM-5_25M service=\
    pppoe
add caller-id=B8:3A:08:DD:DA:C8 comment="Niloy Pal" name=fc468 profile=\
    FCOM-2_8M service=pppoe
add caller-id=CC:2D:21:31:64:70 comment="Joni Akter" name=fc472 profile=\
    FCOM-1_5M service=pppoe
add caller-id=24:2F:D0:8D:AF:53 comment=Sofikul name=fc475 profile=FCOM-1_5M \
    service=pppoe
add caller-id=B8:3A:08:63:87:E0 comment=Najim name=fc196 profile=FCOM-1_5M \
    service=pppoe
add caller-id=DC:8E:8D:5B:01:05 comment="Abu Kawsar Mia Shakil" name=fc309 \
    profile=FCOM-2_8M service=pppoe
add caller-id=90:6A:94:AC:64:DC comment="Alamin Shah" name=fc390 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:33:CA:B1 comment="Narayan Debnath" name=fc217 profile=\
    FCOM-1_5M service=pppoe
add comment="Md. Mehedi Hasan Shamol" name=fc430 profile=FCOM-2_8M service=\
    pppoe
add caller-id=F0:09:0D:F1:39:1D comment="Kajal Bishwa Sharma" name=fc479 \
    profile=FCOM-1_5M service=pppoe
add caller-id=F0:09:0D:F1:5A:FB comment="Ali Raj" name=fc102 profile=\
    FCOM-1_5M service=pppoe
add caller-id=64:EE:B7:0C:F4:45 comment="AVIK DEBNATH" name=fc456 profile=\
    FCOM-1_5M service=pppoe
add caller-id=CC:2D:21:36:A7:27 comment="Badal Shel" name=fc349 profile=\
    FCOM-1_5M service=pppoe
add caller-id=DC:8E:8D:85:CC:31 comment="Shahana Khanam" name=fc481 profile=\
    FCOM-1_5M service=pppoe
add caller-id=DC:8E:8D:85:C9:28 comment=Sidique name=fc483 profile=FCOM-2_8M \
    service=pppoe
add caller-id=D8:44:89:DE:A4:33 comment="MD Sirajul Islam Selim" name=fc484 \
    profile=FCOM-3_12M service=pppoe
add caller-id=D8:32:14:87:39:20 comment="Afiya Sultana/ringki" name=fc132 \
    profile=FCOM-1_5M service=pppoe
add caller-id=C0:06:C3:11:A4:E1 comment="Abu Shweam" name=fc269 profile=\
    FCOM-2_8M service=pppoe
add caller-id=DC:8E:8D:86:54:79 comment="Kabir Ahammad" name=fc274 profile=\
    FCOM-2_8M service=pppoe
add comment="Mahabubul Hoque" name=fc429 profile=FCOM-2_8M service=pppoe
add comment="Anoar Pasha" name=fc485 profile=FCOM-3_12M service=pppoe
add caller-id=04:5E:A4:C5:24:AC comment="Manik  Debnath" name=fc99 profile=\
    FCOM-2_8M service=pppoe
add caller-id=60:83:E7:60:C2:C3 comment="Mirja Al Mamun" name=fc294 profile=\
    FCOM-3_12M service=pppoe
add comment="Akash Sarkar" name=fc486 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:20:29:37 comment="Sarufa Akter" name=fc138 profile=\
    FCOM-2_8M service=pppoe
add caller-id=DC:8E:8D:86:55:B4 comment=Mustakim name=fc489 profile=\
    FCOM-3_12M service=pppoe
add caller-id=B0:19:21:78:BA:E5 comment="Yonus Khan" name=fc490 profile=\
    FCOM-3_12M service=pppoe
add caller-id=B8:3A:08:67:4D:78 comment="\tAsma bagom" name=fc358 profile=\
    FCOM-1_5M service=pppoe
add caller-id=B0:19:21:78:BA:EE comment="Adarsha public High School" name=\
    fc447 profile=FCOM-3_12M service=pppoe
add comment="Adarsha public High School" name=fc476 profile=FCOM-1_5M \
    service=pppoe
add comment="Dipan Khan" name=fc76 profile=FCOM-2_8M service=pppoe
add caller-id=90:9A:4A:06:67:09 comment="Manik Mia" name=fc176 profile=\
    FCOM-4_20M service=pppoe
add caller-id=B8:3A:08:6E:90:E8 comment="Md ahasan" name=fc187 profile=\
    FCOM-1_5M service=pppoe
add caller-id=50:0F:F5:83:A8:27 comment="Shipon Mia" name=fc329 profile=\
    FCOM-1_5M service=pppoe
add comment="Arman Mia" name=fc419 profile=FCOM-1_5M service=pppoe
add caller-id=B8:3A:08:70:F9:49 comment="Madan College-2" name=fc228 profile=\
    FCOM-4_20M service=pppoe
add caller-id=DC:8E:8D:86:55:DE comment="Palash Chandra Pal" name=fc278 \
    profile=FCOM-1_5M service=pppoe
add caller-id=A8:6E:84:9B:36:4B comment="Sohel Rana" name=fc467 profile=\
    FCOM-1_5M service=pppoe
add caller-id=60:83:E7:61:7D:1F comment=" Modon Paurasabha" name=fc62 \
    profile=FCOM-2_8M service=pppoe
add caller-id=DC:8E:8D:86:53:D8 comment="Anower Hossen" name=fc495 profile=\
    FCOM-1_5M service=pppoe
add caller-id=DC:8E:8D:86:55:C9 comment="ROHUL ISLAM" name=fc493 profile=\
    FCOM-1_5M service=pppoe
add name="office " profile=FCOM-5_25M service=pppoe
add caller-id=D8:32:14:A5:FC:F8 comment="Saim Telecom" name=fc54 profile=\
    FCOM-6_30M remote-address=157.119.186.87 service=pppoe
add comment="Selim Khan" name=fc501 profile=FCOM-3_12M service=pppoe
add comment="Sirajul Islam" name=fc503 profile=FCOM-1_5M service=pppoe
add caller-id=D4:01:C3:D8:5B:9D name=modonsblplc profile=FCOM-1_5M service=\
    pppoe
add comment=Rimu name=fc504 profile=FCOM-1_5M service=pppoe
add comment=Saddam name=fc506 profile=FCOM-1_5M service=pppoe
add caller-id=DC:8E:8D:6A:5F:A3 comment="Khadija Akter" name=fc34 profile=\
    FCOM-1_5M service=pppoe
add comment="Jerin Akter" name=fc500 profile=FCOM-1_5M service=pppoe
add caller-id=DC:8E:8D:86:91:5E comment="Anamul Haq" name=fc311 profile=\
    FCOM-1_5M service=pppoe
add caller-id=DC:8E:8D:5D:B4:75 comment="Helim Mia" name=fc341 profile=\
    FCOM-2_8M service=pppoe
add comment="Tipu Sultan" name=fc505 profile=FCOM-4_20M service=pppoe
add comment=Mahabub name=fc511 profile=FCOM-2_8M service=pppoe
add comment="Jamirul Islam" name=fc502 profile=FCOM-2_8M service=pppoe
add caller-id=BC:E0:01:87:39:2C comment="NOWRIN AKANDO" name=fc194 profile=\
    FCOM-2_8M service=pppoe
add comment="Trina khanom" name=fc513 profile=FCOM-2_8M service=pppoe
add comment="Mahabub Alam" name=fc516 profile=FCOM-1_5M service=pppoe
add comment="Towkik Chowdhury" name=fc517 profile=FCOM-1_5M service=pppoe
add caller-id=B4:B0:24:44:04:B9 comment="Mahamuda Akter" name=fc518 profile=\
    FCOM-1_5M service=pppoe
add caller-id=58:D9:D5:9E:8B:48 comment="Ruhul Amin" name=fc519 profile=\
    FCOM-1_5M service=pppoe
add caller-id=60:32:B1:60:AF:A7 comment=Dr.Rafat name=fc521 profile=FCOM-1_5M \
    service=pppoe
add caller-id=50:0F:F5:06:1F:07 comment="Prantu Talukder" name=fc522 profile=\
    FCOM-4_20M service=pppoe
add caller-id=D8:32:14:0F:CC:D8 comment=Ashik name=fc25 profile=FCOM-3_12M \
    service=pppoe
add caller-id=A4:2B:B0:01:3A:0B name=raju42 profile=FCOM-1_5M service=pppoe
add caller-id=50:0F:F5:E9:ED:60 comment="Mijanur Rahman" name=fc524 profile=\
    FCOM-1_5M service=pppoe
add caller-id=DC:8E:8D:6A:60:D7 name=raju06 profile=FCOM-1_5M service=pppoe
add caller-id=5C:A6:E6:4F:A4:8D name=raju05 profile=FCOM-2_8M service=pppoe
add caller-id=DC:8E:8D:B3:DC:98 comment="Md Oli" name=fc410 profile=FCOM-1_5M \
    service=pppoe
add caller-id=DC:8E:8D:B3:E1:76 comment=Samrat name=fc527 profile=FCOM-3_12M \
    service=pppoe
add caller-id=DC:8E:8D:B3:E2:10 comment="Ratul [apex ]" name=fc528 profile=\
    FCOM-2_8M service=pppoe
add caller-id=50:0F:F5:9C:01:27 comment="Abdul Haq" name=fc529 profile=\
    FCOM-2_8M service=pppoe
add comment="Rana Mia" name=fc431 profile=FCOM-3_12M service=pppoe
add caller-id=CC:32:E5:2A:55:F7 comment=Sohag name=fc530 profile=FCOM-3_12M \
    service=pppoe
add caller-id=58:D9:D5:81:EB:98 comment="ANGUR MIA" name=fc128 profile=\
    FCOM-1_5M service=pppoe
add caller-id=5C:62:8B:7E:20:35 name=raju08 profile=FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:E2:03:1C disabled=yes name=raju09 profile=FCOM-1_5M \
    service=pppoe
add caller-id=58:D5:6E:E4:3D:5C comment="Rafiqul Islam" name=fc409 profile=\
    FCOM-1_5M service=pppoe
add caller-id=3C:64:CF:BB:21:27 comment=Rukon name=fc532 profile=FCOM-3_12M \
    service=pppoe
add caller-id=D8:32:14:12:B1:C8 name=raju10 profile=FCOM-1_5M service=pppoe
add caller-id=B8:3A:08:02:0E:A8 comment=Kaicher name=fc533 profile=FCOM-2_8M \
    service=pppoe
add caller-id=D8:44:89:0B:58:79 comment="Tuhin Ahammad" name=fc48 profile=\
    FCOM-2_8M service=pppoe
add caller-id=D8:32:14:77:07:30 name=raju01 profile=FCOM-1_5M service=pppoe
add caller-id=50:0F:F5:BA:95:89 comment="Mahan Debnath" name=fc175 profile=\
    FCOM-2_8M service=pppoe
add caller-id=DC:8E:8D:B3:DE:3C comment="Abdul goni" name=fc39 profile=\
    FCOM-1_5M service=pppoe
add caller-id=AC:15:A2:01:78:4F comment=Dulon name=fc165 profile=FCOM-2_8M \
    service=pppoe
add caller-id=50:0F:F5:06:1E:FF comment="Ali Hossen" name=fc226 profile=\
    FCOM-1_5M service=pppoe
add comment="Himel Mia" name=fc497 profile=FCOM-1_5M service=pppoe
add comment="Eshak Mia" name=fc478 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:0F:CC:F0 comment="Raton Raj" name=fc534 profile=\
    FCOM-1_5M service=pppoe
add name="IFIC Bank" profile=FCOM-1_5M service=pppoe
add caller-id=34:60:F9:6C:EC:A1 name=raju15 profile=FCOM-2_8M service=pppoe
add caller-id=50:0F:F5:3B:D6:49 comment="Al Amin " name=fc124 profile=\
    FCOM-3_12M service=pppoe
add name="Krishi Bank" profile=FCOM-1_5M service=pppoe
add caller-id=5C:62:8B:07:DD:A1 name=raju16 profile=FCOM-1_5M service=pppoe
add caller-id=50:0F:F5:36:43:C0 comment="Abdul guni mia" name=fc535 profile=\
    FCOM-2_8M service=pppoe
add caller-id=CC:2D:21:76:9B:D0 comment="Apel " name=fc537 profile=FCOM-4_20M \
    service=pppoe
add comment=Shohidul name=fc494 profile=FCOM-1_5M service=pppoe
add caller-id=50:0F:F5:06:78:5F comment=Alomgir name=fc441 profile=FCOM-1_5M \
    service=pppoe
add caller-id=3C:CD:57:70:57:D2 name=raju24 profile=FCOM-1_5M service=pppoe
add caller-id=40:ED:00:74:51:5D name=raju25 profile=FCOM-2_8M service=pppoe
add comment="Habia Akter" name=fc462 profile=FCOM-2_8M service=pppoe
add caller-id=60:32:B1:0A:2C:41 name=raju26 profile=FCOM-1_5M service=pppoe
add caller-id=50:0F:F5:83:A8:37 comment="NIRMAN SUMITI" name=fc331 profile=\
    FCOM-1_5M service=pppoe
add comment="Anik (kokon vai)" name=fc491 profile=FCOM-1_5M service=pppoe
add caller-id=DC:73:85:69:CF:9F comment="Jakir Hossen" name=fc330 profile=\
    FCOM-1_5M service=pppoe
add caller-id=50:0F:F5:3D:02:09 name=raju13 profile=FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:AA:95:0A comment=OPI name=fc536 profile=FCOM-1_5M \
    service=pppoe
add caller-id=DC:8E:8D:8E:93:81 comment="Tora Sweet[ Hira mia]" name=fc129 \
    profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:06:F3:40 comment="Sabbir Alam" name=fc284 profile=\
    FCOM-1_5M service=pppoe
add comment=Golap name=fc47 profile=FCOM-2_8M service=pppoe
add caller-id=D8:32:14:DF:2B:98 comment=Sofiq name=fc405 profile=FCOM-2_8M \
    service=pppoe
add caller-id=DC:8E:8D:26:70:A8 name=raju31 profile=FCOM-2_8M service=pppoe
add caller-id=38:6B:1C:0D:5D:4F name=raju32 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:4B:C1:B7 comment="Marjina Aktar" name=fc538 profile=\
    FCOM-3_12M service=pppoe
add comment="Shahidul {Master}" name=fc477 profile=FCOM-2_8M service=pppoe
add caller-id=D8:32:14:DF:2B:F0 comment="Rayhan/ mijan" name=fc314 profile=\
    FCOM-2_8M service=pppoe
add caller-id=C0:06:C3:B6:82:FB comment="Elid Mia" name=fc396 profile=\
    FCOM-2_8M service=pppoe
add caller-id=AC:15:A2:C6:29:D1 comment="Sadaf (Office)" name=fc337 profile=\
    FCOM-4_20M service=pppoe
add name=fc499 profile=FCOM-2_8M service=pppoe
add caller-id=D8:32:14:9A:9F:D8 comment=Nilay name=fc471 profile=FCOM-2_8M \
    service=pppoe
add comment="Juntu Mia/ Biplob" name=fc482 profile=FCOM-3_12M service=pppoe
add caller-id=DC:8E:8D:5C:F4:27 comment="Rahmot Ullah" name=fc296 profile=\
    FCOM-2_8M service=pppoe
add caller-id=B8:3A:08:FE:9C:A8 name=raju21 profile=FCOM-1_5M service=pppoe
add comment="Sujan mia" name=fc507 profile=FCOM-2_8M service=pppoe
add caller-id=D8:32:14:20:29:1F comment="Hasibul Islam { Aalvi }" name=fc158 \
    profile=FCOM-2_8M service=pppoe
add caller-id=DC:8E:8D:B3:DE:74 comment="Matri Jewelers (Home)" name=fc277 \
    profile=FCOM-2_8M service=pppoe
add comment="MD Rafiqul Islam Ridoy { }" name="abl teasree" profile=\
    FCOM-4_20M service=pppoe
add caller-id=50:0F:F5:06:F5:57 comment=Nibir name=fc540 profile=FCOM-2_8M \
    service=pppoe
add caller-id=20:23:51:D2:45:89 comment="Fardin Khan" name=fc541 profile=\
    FCOM-2_8M service=pppoe
add caller-id=DC:8E:8D:AB:0C:D7 comment="Pavel Master" name=fc543 profile=\
    FCOM-1_5M service=pppoe
add caller-id=EC:75:0C:DE:6E:AD comment="Sagor Chandra Das" name=fc367 \
    profile=FCOM-1_5M service=pppoe
add caller-id=8C:DE:F9:DD:B4:E6 comment="Ariful Islam" name=fc544 profile=\
    FCOM-1_5M service=pppoe
add caller-id=DC:8E:8D:06:43:9F comment="Sujit Kormokar {Dokan}" name=fc545 \
    profile=FCOM-1_5M service=pppoe
add caller-id=DC:8E:8D:AB:20:25 comment=Tushar name=fc546 profile=FCOM-2_8M \
    service=pppoe
add caller-id=40:AE:30:2B:9B:9D comment="Abdul Haq" name=fc547 profile=\
    FCOM-3_12M service=pppoe
add caller-id=04:5E:A4:F0:FF:71 comment=Selim name=fc548 profile=FCOM-1_5M \
    service=pppoe
add caller-id=D8:32:14:73:96:F0 comment=Nadim name=fc549 profile=FCOM-1_5M \
    service=pppoe
add caller-id=3C:64:CF:26:21:8B comment="Johirul Alom Khandaker" name=fc122 \
    profile=FCOM-2_8M service=pppoe
add caller-id=B8:3A:08:8C:90:9F comment=Ridoy name=fc27 profile=FCOM-3_12M \
    service=pppoe
add caller-id=D8:32:14:3C:E4:60 comment=Imran name=fc03 profile=FCOM-1_5M \
    service=pppoe
add caller-id=D8:32:14:9A:A1:08 comment="\tMirja NIlay" name=fc365 profile=\
    FCOM-2_8M service=pppoe
add caller-id=08:8A:F1:09:3B:03 comment="Tamanna Akter" name=fc552 profile=\
    FCOM-3_12M service=pppoe
add caller-id=E8:4D:74:EC:5C:6D comment="Mirja Abdul Helim" name=fc553 \
    profile=FCOM-1_5M service=pppoe
add caller-id=B8:3A:08:7C:09:6F comment="Abdul Karim Khan" name=fc554 \
    profile=FCOM-3_12M service=pppoe
add caller-id=38:6B:1C:B1:F1:95 comment="Mubarak Hosen" name=fc555 profile=\
    FCOM-2_8M service=pppoe
add caller-id=30:68:93:D0:FF:56 comment=Naim name=fc556 profile=FCOM-2_8M \
    service=pppoe
add caller-id=50:0F:F5:3B:D6:09 name=raju03 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:8C:53:80 name=raju33 profile=FCOM-1_5M service=pppoe
add caller-id=50:0F:F5:06:F5:5F name=raju34 profile=FCOM-1_5M service=pppoe
add caller-id=A8:29:48:6D:74:53 name=raju36 profile=FCOM-1_5M service=pppoe
add caller-id=B0:19:21:42:B1:19 name=raju38 profile=FCOM-1_5M service=pppoe
add caller-id=50:0F:F5:52:01:60 name=raju39 profile=FCOM-1_5M service=pppoe
add caller-id=3C:CD:57:70:62:86 name=raju40 profile=FCOM-1_5M service=pppoe
add caller-id=50:0F:F5:06:F5:77 name=raju41 profile=FCOM-1_5M service=pppoe
add caller-id=B8:3A:08:91:B6:38 name=raju22 profile=FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:87:29:2D name=raju43 profile=FCOM-1_5M service=pppoe
add caller-id=60:A4:B7:DD:0E:73 comment="Khandaker Robin" name=fc557 profile=\
    FCOM-2_8M service=pppoe
add caller-id=D8:32:14:E0:BF:50 comment="Rajib Pal {Dokan}" name=fc558 \
    profile=FCOM-2_8M service=pppoe
add caller-id=B0:19:21:78:BA:D6 name=raju44 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:73:96:E0 comment="\tKartik Pal" name=fc386 profile=\
    FCOM-2_8M service=pppoe
add comment="\tMD Rafiqul Islam Ridoy Krishi Bank" name=bkbmodon profile=\
    FCOM-4_20M service=pppoe
add caller-id=50:0F:F5:36:43:D8 comment="\tSharmin Sultana" name=fc368 \
    profile=FCOM-2_8M service=pppoe
add caller-id=BC:62:CE:DE:10:C4 comment="Eyashin [Polish]" name=fc310 \
    profile=FCOM-1_5M service=pppoe
add caller-id=04:5E:A4:F1:53:40 name=raju29 profile=FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:07:2E:A7 comment=Ratan name=fc244 profile=FCOM-1_5M \
    service=pppoe
add caller-id=D8:32:14:1D:CF:80 comment="\tShafayet" name=fc375 profile=\
    FCOM-1_5M service=pppoe
add caller-id=50:0F:F5:36:43:C8 comment=Shapaly name=fc92 profile=FCOM-1_5M \
    service=pppoe
add caller-id=DC:8E:8D:AB:0F:D9 name=raju12 profile=FCOM-1_5M service=pppoe
add caller-id=60:83:E7:B9:9F:F3 comment="Rana bai" name=fc345 profile=\
    FCOM-1_5M service=pppoe
add caller-id=DC:8E:8D:AB:0F:E0 comment="Bai Bai" name=fc248 profile=\
    FCOM-1_5M service=pppoe
add caller-id=A8:29:48:6D:74:29 comment="Army Shows" name=fc318 profile=\
    FCOM-3_12M service=pppoe
add caller-id=50:0F:F5:29:DF:69 comment=Alamgir name=fc302 profile=FCOM-1_5M \
    service=pppoe
add caller-id=48:22:54:70:6E:3E comment=Arafat name=fc525 profile=FCOM-1_5M \
    service=pppoe
add comment="Rasel mia" name=fc496 profile=FCOM-1_5M service=pppoe
add comment="Hiron Mia" name=fc492 profile=FCOM-2_8M service=pppoe
add caller-id=B8:3A:08:7C:09:7F comment="Ambiya Akter" name=fc65 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:3D:56:90 comment="Lelin Home" name=fc523 profile=\
    FCOM-1_5M service=pppoe
add caller-id=3C:52:A1:AE:45:CD comment=Rocky name=fc313 profile=FCOM-2_8M \
    service=pppoe
add caller-id=BC:62:CE:1B:9F:71 comment=Habibulla name=fc144 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:E0:BF:60 comment=Durjoy name=fc400 profile=FCOM-1_5M \
    service=pppoe
add caller-id=D8:32:14:3E:76:70 comment="Mitu Nag" name=fc470 profile=\
    FCOM-1_5M service=pppoe
add comment="Joy Durga Jewelers" name=fc512 profile=FCOM-1_5M service=pppoe
add caller-id=CC:2D:21:D2:32:B0 comment=Shongkor name=fc550 profile=FCOM-1_5M \
    service=pppoe
add comment="Shamim mia /" name=fc488 profile=FCOM-2_8M service=pppoe
add caller-id=D8:32:14:68:DB:39 comment="Mohan Mia/ " name=fc322 profile=\
    FCOM-1_5M service=pppoe
add comment="Nasima Akher" name=fc416 profile=FCOM-1_5M service=pppoe
add caller-id=EC:75:0C:19:B4:51 comment="Kasem Mia" name=fc160 profile=\
    FCOM-1_5M service=pppoe
add caller-id=8C:90:2D:19:35:85 comment="Ziaur Rahman " name=fc236 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:3E:76:D0 name=raju14 profile=FCOM-1_5M service=pppoe
add caller-id=EC:75:0C:C1:2B:CF name=raju45 profile=FCOM-1_5M service=pppoe
add caller-id=B8:3A:08:7C:11:1F comment=Rayhan name=fc559 profile=FCOM-2_8M \
    service=pppoe
add caller-id=D8:32:14:3D:56:58 comment="Chinmoy paul" name=fc560 profile=\
    FCOM-2_8M service=pppoe
add caller-id=90:6A:94:AD:60:D4 comment="Tufael Ahammad" name=fc561 profile=\
    FCOM-2_8M service=pppoe
add caller-id=88:BD:09:4F:85:1F comment=Tamim name=fc562 profile=FCOM-2_8M \
    service=pppoe
add caller-id=88:BD:09:4F:84:E0 comment="Mahabub Mia" name=fc563 profile=\
    FCOM-2_8M service=pppoe
add caller-id=68:FF:7B:E0:1B:D1 comment="Md.Habibur Rahaman" name=fc247 \
    profile=FCOM-2_8M service=pppoe
add caller-id=DC:8E:8D:4B:E5:2A name=raju23 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:0F:2B:78 comment="Monir Hossen" name=fc255 profile=\
    FCOM-1_5M service=pppoe
add comment=Masum name=fc515 profile=FCOM-1_5M service=pppoe
add comment=Sumon name=fc208 profile=FCOM-2_8M service=pppoe
add comment="Shovo Islam Kuka" name=fc425 profile=FCOM-1_5M service=pppoe
add caller-id=CC:2D:21:DC:9D:00 comment=Santu name=fc542 profile=FCOM-1_5M \
    service=pppoe
add caller-id=88:BD:09:59:3C:89 comment="Hussein kabir" name=fc258 profile=\
    FCOM-2_8M service=pppoe
add caller-id=BC:07:1D:C9:55:D5 name=raju46 profile=FCOM-1_5M service=pppoe
add caller-id=50:0F:F5:36:43:50 name=raju49 profile=FCOM-1_5M service=pppoe
add caller-id=A8:29:48:02:AE:F2 name=raju50 profile=FCOM-2_8M service=pppoe
add caller-id=E0:D3:62:11:C4:99 name=raju52 profile=FCOM-1_5M service=pppoe
add caller-id=A0:7E:09:15:6E:0F name=raju54 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:55:D8:BF name=raju56 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:0F:2B:58 name=raju57 profile=FCOM-1_5M service=pppoe
add caller-id=1A:CF:BD:37:10:D2 name=raju59 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:94:E0:5F name=raju60 profile=FCOM-2_8M service=pppoe
add caller-id=50:0F:F5:06:F5:6F comment="Rafikul Islam" name=fc353 profile=\
    FCOM-1_5M service=pppoe
add caller-id=4C:D7:C8:7E:A7:9A comment=Liton name=fc565 profile=FCOM-2_8M \
    service=pppoe
add caller-id=D8:32:14:3D:56:80 comment="Juton F" name=fc566 profile=\
    FCOM-1_5M service=pppoe
add caller-id=3C:84:6A:6D:35:47 comment=Oliullah name=fc567 profile=FCOM-2_8M \
    service=pppoe
add caller-id=DC:8E:8D:5D:B6:2E comment="Shamol Dhotto" name=fc568 profile=\
    FCOM-2_8M service=pppoe
add caller-id=DC:8E:8D:AB:11:30 comment="UHFPO (THC)" name=fc569 profile=\
    FCOM-3_12M service=pppoe
add caller-id=3C:CD:57:79:F3:E0 comment=Phahad name=fc570 profile=FCOM-1_5M \
    service=pppoe
add caller-id=E4:C3:2A:58:5A:5D comment="Najmul Hoq" name=fc573 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:94:E2:0F comment=Alauddin name=fc574 profile=FCOM-1_5M \
    service=pppoe
add caller-id=38:6B:1C:B2:21:FB comment=Sajahan name=fc575 profile=FCOM-2_8M \
    service=pppoe
add caller-id=F0:A7:31:D3:CE:15 comment="Mizanur Rahman" name=fc576 profile=\
    FCOM-1_5M service=pppoe
add caller-id=B8:3A:08:7C:0B:EF comment="Mahmuda Khanom" name=fc577 profile=\
    FCOM-1_5M service=pppoe
add caller-id=9C:9D:7E:8C:2B:21 comment="Bipul (Home)" name=fc287 profile=\
    FCOM-1_5M service=pppoe
add caller-id=58:D9:D5:84:2A:CF comment="Likon Mia " name=fc32 profile=\
    FCOM-1_5M service=pppoe
add caller-id=3C:6A:D2:B8:0E:F9 name=hk1001 profile=FCOM-3_12M service=pppoe
add caller-id=CC:2D:21:DC:9C:F8 name=hk1003 profile=FCOM-1_5M service=pppoe
add caller-id=58:D5:6E:D0:D7:F7 comment="Shahjahan Kabir" name=sp1006 \
    profile=FCOM-4_20M service=pppoe
add caller-id=CC:2D:21:F5:B7:00 name=raju04 profile=FCOM-1_5M service=pppoe
add caller-id=40:3F:8C:89:DF:D5 comment=Safiqul name=fc298 profile=FCOM-1_5M \
    service=pppoe
add caller-id=88:BD:09:0D:44:1E comment="Anonto pal" name=fc316 profile=\
    FCOM-1_5M service=pppoe
add caller-id=B4:0F:3B:35:3E:97 comment="Arju Mia" name=fc452 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:1E:B0:10 comment=Sonali name=fc83 profile=FCOM-2_8M \
    service=pppoe
add caller-id=60:83:E7:11:FB:C9 comment="Matlobur Rahman" name=fc96 profile=\
    FCOM-3_12M service=pppoe
add caller-id=D8:32:14:1E:85:78 comment=Arifuzzaman name=fc412 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:87:32:2C comment=Alinur name=fc339 profile=FCOM-1_5M \
    service=pppoe
add caller-id=58:D9:D5:81:EB:88 comment=Jusna name=fc384 profile=FCOM-1_5M \
    service=pppoe
add caller-id=D8:32:14:94:E1:DF comment="Shahjahan Khan Patan" name=fc579 \
    profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:94:E1:B7 comment="Akij biri" name=fc580 profile=\
    FCOM-3_12M service=pppoe
add caller-id=D8:32:14:A6:4B:10 comment="Hadiul Islam" name=fc581 profile=\
    FCOM-1_5M service=pppoe
add caller-id=C0:C9:E3:D8:2C:4F comment=Nafijh name=fc582 profile=FCOM-1_5M \
    service=pppoe
add caller-id=50:0F:F5:82:89:6F comment=Jesmin name=fc583 profile=FCOM-1_5M \
    service=pppoe
add caller-id=30:68:93:69:29:01 comment="Jahirul Islam Khan" name=fc585 \
    profile=FCOM-1_5M service=pppoe
add caller-id=E0:D3:62:72:AA:01 comment=Rana name=sp1001 profile=FCOM-4_20M \
    service=pppoe
add caller-id=D8:32:14:95:6E:E7 name=sp1002 profile=FCOM-3_12M service=pppoe
add caller-id=DC:62:79:83:83:F9 name=sp1003 profile=FCOM-1_5M service=pppoe
add caller-id=DC:62:79:83:83:85 name=sp1005 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:31:EC:A8 comment="Nurun nahar" name=fc166 profile=\
    FCOM-1_5M service=pppoe
add caller-id=04:95:E6:58:58:70 name=sp1008 profile=FCOM-2_8M service=pppoe
add caller-id=D8:32:14:DD:50:10 name=sp1007 profile=FCOM-1_5M service=pppoe
add caller-id=C8:3A:35:5B:2C:78 comment="shajon miya" name=sp1009 profile=\
    FCOM-3_12M service=pppoe
add caller-id=EC:75:0C:AD:15:B3 name=sp1010 profile=FCOM-2_8M service=pppoe
add caller-id=20:23:51:77:04:29 comment="Babul " name=fc195 profile=FCOM-1_5M \
    service=pppoe
add caller-id=D8:32:14:41:84:47 comment=Antor name=sp1011 profile=FCOM-1_5M \
    service=pppoe
add caller-id=60:32:B1:87:92:53 comment="K.M. Al-Nahid" name=fc29 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:A9:D9:53 comment=MEHEDI name=fc91 profile=FCOM-1_5M \
    service=pppoe
add comment=Nijamul name=fc424 profile=FCOM-2_8M service=pppoe
add caller-id=EC:75:0C:15:4F:46 comment=Polash name=fc321 profile=FCOM-4_20M \
    service=pppoe
add caller-id=D8:32:14:41:86:07 comment="Target Tipu" name=sp1012 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:C9:0A:A9 comment="Azizul Haque" name=sp1013 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:67:C8:C9 comment="Abul Fatal" name=sp1014 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:06:F3:30 comment="Rahim Uddin" name=sp1015 profile=\
    FCOM-2_8M service=pppoe
add comment="Abir Hasan" name=fc586 profile=FCOM-1_5M service=pppoe
add caller-id=CC:2D:21:17:D6:C0 comment="Ruma Akter" name=fc587 profile=\
    FCOM-1_5M service=pppoe
add caller-id=B4:B0:24:07:06:4B name=raju07 profile=FCOM-1_5M service=pppoe
add caller-id=90:6A:94:AD:B9:C0 name=hk1005 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:41:67:87 comment=Hanif name=sp1016 profile=FCOM-1_5M \
    service=pppoe
add caller-id=E8:65:D4:08:87:48 comment="Bristi Akter" name=fc77 profile=\
    FCOM-2_8M service=pppoe
add caller-id=38:6B:1C:B2:3E:ED comment="Jahedul Islam / Rural Electricity" \
    name=fc324 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:31:B0:68 comment="Basona Akter" name=fc46 profile=\
    FCOM-1_5M service=pppoe
add comment=Rasel name=fc427 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:A4:1D:87 comment="Parvej Ahammad" name=fc589 profile=\
    FCOM-1_5M service=pppoe
add comment=Nibir name=fc590 profile=FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:8A:31:20 comment=Imran name=fc444 profile=FCOM-1_5M \
    service=pppoe
add comment="Sobuj mia" name=fc426 profile=FCOM-3_12M service=pppoe
add comment=Masum name=fc514 profile=FCOM-2_8M service=pppoe
add caller-id=EC:41:18:E3:C3:70 comment="Anajul Haque" name=fc392 profile=\
    FCOM-1_5M service=pppoe
add caller-id=CC:32:E5:8E:5F:CC comment="Samir Bhuson Baisya" name=fc250 \
    profile=FCOM-1_5M service=pppoe
add caller-id=BC:62:CE:CC:94:8B comment="Rumen Mia" name=fc333 profile=\
    FCOM-1_5M service=pppoe
add caller-id=CC:32:E5:03:C0:EB comment="Roman Traders" name=fc223 profile=\
    FCOM-1_5M service=pppoe
add caller-id=80:AF:CA:AD:FF:E1 comment=Abulayes name=fc08 profile=FCOM-1_5M \
    service=pppoe
add caller-id=BC:62:CE:CD:29:64 comment="Chumki Akter" name=fc242 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:A4:19:07 comment="Ingil Mia" name=fc591 profile=\
    FCOM-4_20M service=pppoe
add caller-id=D8:32:14:A7:EC:D9 comment="Mahbubur Rahman" name=fc592 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:2F:EE:3F comment="Jamirul Islam" name=sp1017 profile=\
    FCOM-1_5M service=pppoe
add comment="Ahmed Ali" name=sp1018 profile=FCOM-2_8M service=pppoe
add caller-id=30:16:9D:EE:C6:E3 name=raju61 profile=FCOM-1_5M service=pppoe
add caller-id=18:D6:C7:55:40:21 name=raju64 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:32:52:B8 name=raju65 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:41:85:8F name=raju66 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:32:60:10 comment="Ma Enterprise" name=fc455 profile=\
    FCOM-2_8M service=pppoe
add caller-id=08:40:F3:1E:C8:1C comment="Nur Mohammad" name=fc594 profile=\
    FCOM-2_8M service=pppoe
add caller-id=3C:6A:D2:1B:2D:4F name=raju68 profile=FCOM-1_5M service=pppoe
add caller-id=58:D9:D5:29:60:27 name=raju70 profile=FCOM-1_5M service=pppoe
add caller-id=A8:29:48:6D:83:E0 name=raju71 profile=FCOM-2_8M service=pppoe
add comment="Faruk Mia" name=fc508 profile=FCOM-1_5M service=pppoe
add caller-id=E0:D3:62:11:EC:B0 comment="\tRofiq Enterprise" name=fc596 \
    profile=FCOM-2_8M service=pppoe
add caller-id=D8:32:14:31:44:E8 comment=Safayet name=fc598 profile=FCOM-2_8M \
    service=pppoe
add caller-id=10:5A:95:60:0A:75 comment="SUFIYA HOSPITAL " name=fc599 \
    profile=FCOM-6_30M service=pppoe
add caller-id=08:40:F3:58:D0:97 comment="Fariya Jahan" name=fc90 profile=\
    FCOM-2_8M service=pppoe
add comment=Rabbi name=fc600 profile=FCOM-2_8M service=pppoe
add caller-id=88:BD:09:2C:06:74 comment="Atabur Rahman" name=fc601 profile=\
    FCOM-3_12M service=pppoe
add caller-id=AC:15:A2:D1:9E:11 comment="Md rasel miya" name=sp1019 profile=\
    FCOM-2_8M service=pppoe
add caller-id=D8:32:14:32:00:B8 comment="Rahmim Mia" name=fc602 profile=\
    FCOM-2_8M service=pppoe
add caller-id=78:20:51:4A:FE:34 comment="Motiur Rahman" name=fc604 profile=\
    FCOM-2_8M service=pppoe
add caller-id=BC:62:CE:CD:2B:49 comment="moklachur Mahman" name=fc605 \
    profile=FCOM-2_8M service=pppoe
add caller-id=D8:32:14:73:3D:CF comment="Mst Shirin Akter" name=fc74 profile=\
    FCOM-1_5M service=pppoe
add caller-id=08:40:F3:8E:5A:0F name=raju20 profile=FCOM-2_8M service=pppoe
add caller-id=98:C7:A4:2B:CD:33 comment="\tObiman" name=fc606 profile=\
    FCOM-2_8M service=pppoe
add caller-id=04:5E:A4:C5:24:9D comment="\tProsanto Bonik" name=fc607 \
    profile=FCOM-2_8M service=pppoe
add caller-id=08:40:F3:1C:87:AC comment="\tKripesh Talukder" name=fc608 \
    profile=FCOM-2_8M service=pppoe
add caller-id=F0:09:0D:3A:CE:9F comment="\tDurjo" name=fc609 profile=\
    FCOM-2_8M service=pppoe
add caller-id=F0:09:0D:3A:CF:29 comment="Parul begum" name=fc610 profile=\
    FCOM-2_8M service=pppoe
add caller-id=58:D9:D5:81:E8:70 comment="Ruktom mia" name=fc611 profile=\
    FCOM-2_8M service=pppoe
add comment="MD Asadullah" disabled=yes name=fc612 profile=FCOM-2_8M service=\
    pppoe
add comment="\tRana Bai" name=fc613 profile=FCOM-2_8M service=pppoe
add caller-id=08:40:F3:8E:5A:07 comment="Roman Mia" name=fc614 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:1F:66:80 comment="Bipul Sarker" name=fc615 profile=\
    FCOM-2_8M service=pppoe
add caller-id=B8:3A:08:FF:C4:80 comment=Hridoy name=fc616 profile=FCOM-2_8M \
    service=pppoe
add caller-id=EC:75:0C:ED:01:F3 comment="Rupok pondit" name=fc617 profile=\
    FCOM-2_8M service=pppoe
add caller-id=50:3D:D1:3B:90:B1 comment="Abir Computer" name=fc618 profile=\
    FCOM-2_8M service=pppoe
add caller-id=F0:B4:D2:3E:FA:D3 comment=Faysal name=sp1020 profile=FCOM-2_8M \
    service=pppoe
add caller-id=60:32:B1:18:B6:F4 comment=Sayef name=hk1006 profile=FCOM-1_5M \
    service=pppoe
add caller-id=88:BD:09:5A:BA:4F comment="Hazragati School" name=hk1007 \
    profile=FCOM-3_12M service=pppoe
add caller-id=3C:CD:57:70:62:82 name=raju58 profile=FCOM-1_5M service=pppoe
add caller-id=08:40:F3:95:53:8F comment="Anwar Pasha " name=fc619 profile=\
    FCOM-2_8M service=pppoe
add caller-id=E4:C3:2A:62:96:3F comment="Samiur Rahaman " name=fc621 profile=\
    FCOM-2_8M service=pppoe
add caller-id=78:20:51:E3:6E:BF comment="NURE AKBAR" name=fc623 profile=\
    FCOM-2_8M service=pppoe
add caller-id=08:40:F3:82:8C:A8 comment="Motiur Rahman mintu" name=sp1021 \
    profile=FCOM-2_8M service=pppoe
add caller-id=08:40:F3:6C:04:FF comment="\tJesmin Akter" name=sp1022 profile=\
    FCOM-2_8M service=pppoe
add caller-id=D8:32:14:31:EB:00 comment="Sahapur dewan bar" name=sp1023 \
    profile=FCOM-2_8M service=pppoe
add caller-id=90:6A:94:AC:94:E4 comment="Farid miya" name=hk1008 profile=\
    FCOM-2_8M service=pppoe
add caller-id=D8:32:14:69:EB:79 comment="Shohan Mia" name=hk1009 profile=\
    FCOM-2_8M service=pppoe
add caller-id=08:40:F3:90:87:88 comment="Zonaedh Ahmmed" name=fc09 profile=\
    FCOM-2_8M service=pppoe
add caller-id=90:6A:94:AC:6F:68 comment=Ripon name=fc624 profile=FCOM-2_8M \
    service=pppoe
add caller-id=D8:32:14:3D:3B:E0 comment=Billal name=fc33 profile=FCOM-2_8M \
    service=pppoe
add caller-id=F4:F2:6D:90:3A:55 comment="Delwer Hossen" name=fc625 profile=\
    FCOM-2_8M service=pppoe
add caller-id=3C:6A:D2:1B:9D:15 comment="Alamin Talukdar" name=fc626 profile=\
    FCOM-3_12M service=pppoe
add caller-id=14:4D:67:2F:EA:95 comment="Tuhin telecom" name=fc627 profile=\
    FCOM-2_8M service=pppoe
add caller-id=5C:62:8B:7E:1F:C1 comment="Bipul Mia" name=fc628 profile=\
    FCOM-2_8M service=pppoe
add caller-id=A8:29:48:6D:15:9A comment="Samiul Haider Safi" name=fc629 \
    profile=FCOM-2_8M service=pppoe
add caller-id=50:0F:F5:B2:1F:B0 comment=Sohel name=fc630 profile=FCOM-2_8M \
    service=pppoe
add caller-id=CC:2D:21:3F:41:8F comment="Hena Akther" name=fc631 profile=\
    FCOM-2_8M service=pppoe
add caller-id=08:40:F3:95:53:77 comment=Amit name=fc638 profile=FCOM-2_8M \
    service=pppoe
add caller-id=80:AF:CA:3C:EF:6D comment=Jasim name=fc312 profile=FCOM-2_8M \
    service=pppoe
add name=fc459 profile=FCOM-4_20M remote-address=157.119.186.85 service=pppoe
add name=fc457 profile=FCOM-7_40M service=pppoe
add name=fc460 profile=FCOM-7_40M service=pppoe
add name=fc461 profile=FCOM-7_40M service=pppoe
add caller-id=50:D4:F7:C8:6F:D3 comment="\tSuman" name=op01 profile=FCOM-1_5M \
    service=pppoe
add comment=Opu name=op04 profile=FCOM-2_8M service=pppoe
add caller-id=38:6B:1C:0D:61:EB comment="Krishi Bank" name=op06 profile=\
    FCOM-1_5M service=pppoe
add caller-id=80:AF:CA:23:95:BD comment="Zainuddin member" name=op07 profile=\
    FCOM-2_8M service=pppoe
add caller-id=80:AF:CA:8C:E6:22 comment=op09 name=op09 profile=FCOM-1_5M \
    service=pppoe
add comment=op10 name=op10 profile=FCOM-3_12M service=pppoe
add comment=op11 name=op11 profile=FCOM-2_8M service=pppoe
add caller-id=D8:32:14:69:F5:A9 name=raju72 profile=FCOM-2_8M service=pppoe
add caller-id=50:D4:F7:ED:08:61 name=raju73 profile=FCOM-2_8M service=pppoe
add caller-id=08:40:F3:6C:05:0F name=raju74 profile=FCOM-2_8M service=pppoe
add caller-id=78:20:51:E3:64:C9 name=raju75 profile=FCOM-2_8M service=pppoe
add caller-id=BC:62:CE:1B:17:0D name=raju76 profile=FCOM-2_8M service=pppoe
add caller-id=DC:8E:8D:51:BB:83 name=raju77 profile=FCOM-2_8M service=pppoe
add caller-id=60:83:E7:61:7D:0F name=raju78 profile=FCOM-2_8M service=pppoe
add caller-id=78:20:51:6D:E7:69 comment="Juyel sarker" name=fc634 profile=\
    FCOM-1_5M service=pppoe
add caller-id=78:20:51:AE:13:F1 comment="Baccu Mia" name=fc635 profile=\
    FCOM-2_8M service=pppoe
add caller-id=E0:D3:62:11:EC:F2 comment="Jamil uddin" name=fc637 profile=\
    FCOM-2_8M service=pppoe
add caller-id=08:40:F3:90:28:00 comment="Jabul Miah" name=fc633 profile=\
    FCOM-1_5M service=pppoe
add caller-id=28:87:BA:68:05:11 comment=Ajijul name=fc639 profile=FCOM-1_5M \
    service=pppoe
add caller-id=08:40:F3:82:D8:60 comment=Tawhid name=fc636 profile=FCOM-1_5M \
    service=pppoe
add caller-id=CC:2D:21:77:D4:B8 comment=Mariya name=fc640 profile=FCOM-1_5M \
    service=pppoe
add caller-id=30:16:9D:E0:54:B9 comment="Tutan Shil" name=fc641 profile=\
    FCOM-1_5M service=pppoe
add caller-id=08:40:F3:8F:F5:BF comment="Sabikur Rahman" name=fc642 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:70:9C:79 name=niranjon profile=FCOM-2_8M service=pppoe
add caller-id=58:D9:D5:2A:95:F8 comment="Arif cosmetic" name=fc142 profile=\
    FCOM-2_8M service=pppoe
add caller-id=08:40:F3:6D:9A:E0 comment="Sintia/Rupa Akter" name=raju11 \
    profile=FCOM-1_5M service=pppoe
add caller-id=78:8C:B5:49:18:90 comment=Bappy name=fc571 profile=FCOM-1_5M \
    service=pppoe
add comment="MA Telicom/ Sony" name=fc361 profile=FCOM-2_8M service=pppoe
add caller-id=88:BD:09:14:56:8D comment="Sony/Jony Pal Home" name=fc551 \
    profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:E4:C0:20 name=raju35 profile=FCOM-1_5M service=pppoe
add caller-id=1C:3B:F3:69:57:29 comment=Haresh name=fc04 profile=FCOM-1_5M \
    service=pppoe
add caller-id=78:20:51:E3:6E:C8 comment="Coffee Miya" name=fc643 profile=\
    FCOM-2_8M service=pppoe
add caller-id=08:40:F3:6C:04:F7 comment="Roni Talukder" name=sp1024 profile=\
    FCOM-1_5M service=pppoe
add comment="Md azharul Islam " name=hk1010 profile=FCOM-2_8M service=pppoe
add caller-id=08:40:F3:6D:9A:D8 comment="\tPappu" name=sp1025 profile=\
    FCOM-2_8M service=pppoe
add caller-id=D8:32:14:73:3D:BF name=raju69 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:9A:A0:58 name=raju27 profile=FCOM-2_8M service=pppoe
add caller-id=F0:A7:31:1C:92:C1 name=raju79 profile=FCOM-2_8M service=pppoe
add caller-id=08:40:F3:1E:09:DC comment=Mahadi name=fc644 profile=FCOM-1_5M \
    service=pppoe
add caller-id=BC:07:1D:C9:55:A8 name=sp1026 profile=FCOM-1_5M service=pppoe
add caller-id=CC:32:E5:F6:D2:F5 comment="Monjil miya." name=sp1027 profile=\
    FCOM-1_5M service=pppoe
add caller-id=08:40:F3:20:6C:AC comment=Alamine name=fc647 profile=FCOM-1_5M \
    service=pppoe
add caller-id=08:40:F3:95:53:9F comment="\tRoshun ali" name=fc646 profile=\
    FCOM-1_5M service=pppoe
add caller-id=1C:61:B4:DC:02:D1 comment="Sahriar faruk" name=fc648 profile=\
    FCOM-1_5M service=pppoe
add caller-id=98:25:4A:F5:98:B7 comment="Bike zoon " name=fc649 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:62:CE:CD:2C:BB comment="Dipon ( )" name=fc645 profile=\
    FCOM-3_12M service=pppoe
add caller-id=30:16:9D:EE:9B:81 name=raju62 profile=FCOM-1_5M service=pppoe
add caller-id=04:95:E6:95:29:98 comment="\tKaium" name=fc651 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:67:69:E9 comment=Akbar name=fc652 profile=FCOM-1_5M \
    service=pppoe
add caller-id=08:40:F3:6B:C8:0F comment="Palak baisya" name=fc653 profile=\
    FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:10:B4:07 comment=Rubel name=fc654 profile=FCOM-1_5M \
    service=pppoe
add caller-id=08:40:F3:89:BA:AF comment=Zakir name=fc655 profile=FCOM-1_5M \
    service=pppoe
add caller-id=E4:FA:C4:BA:F9:C4 comment="\tMijan " name=fc656 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:6A:9C:39 comment="Aminul islam" name=fc657 profile=\
    FCOM-1_5M service=pppoe
add caller-id=08:40:F3:90:10:C0 comment="Ajonto roy" name=fc658 profile=\
    FCOM-1_5M service=pppoe
add caller-id=CC:32:E5:03:BE:B4 comment=Sopon name=fc650 profile=FCOM-1_5M \
    service=pppoe
add caller-id=78:20:51:92:AF:23 comment="Dulena membar" name=sp1028 profile=\
    FCOM-1_5M service=pppoe
add caller-id=08:40:F3:95:53:5F comment=Sadi name=sp1029 profile=FCOM-2_8M \
    service=pppoe
add caller-id=08:40:F3:E2:2E:E0 comment="Debashish paul" name=fc660 profile=\
    FCOM-1_5M service=pppoe
add caller-id=08:40:F3:E2:3D:28 comment=Rahat name=fc659 profile=FCOM-2_8M \
    service=pppoe
add caller-id=BC:E0:01:AE:0D:97 comment=Sohag name=fc661 profile=FCOM-3_12M \
    service=pppoe
add caller-id=D8:32:14:2A:DB:D9 comment=Kawsar name=fc572 profile=FCOM-3_12M \
    service=pppoe
add caller-id=54:AF:97:B5:EA:87 comment="Md.Shidul Islam [polish]" name=fc232 \
    profile=FCOM-1_5M service=pppoe
add caller-id=88:BD:09:12:37:CE comment="Bijoy paul" name=fc662 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:2A:98:A9 comment="Nantu Majumdar " name=fc663 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:44:89:97:D2:51 comment="Buty " name=fc664 profile=FCOM-1_5M \
    service=pppoe
add caller-id=08:40:F3:E2:3D:20 comment="Md Riyad" name=sp1030 profile=\
    FCOM-3_12M service=pppoe
add caller-id=D8:32:14:2C:1E:89 comment="Roki miya" name=sp1031 profile=\
    FCOM-1_5M service=pppoe
add caller-id=90:6A:94:AD:5E:E4 comment="Md saharul islam" name=sp1032 \
    profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:2A:98:C9 comment="Sotto sornokar" name=fc666 profile=\
    FCOM-2_8M service=pppoe
add caller-id=98:BA:5F:39:C9:BF comment="BGB Shahalom" name=fc665 profile=\
    FCOM-1_5M service=pppoe
add caller-id=A8:29:48:8B:AB:6A comment="Md. Al Amin" name=hk1002 profile=\
    FCOM-1_5M service=pppoe
add caller-id=08:40:F3:20:64:BC comment="Azimul Hoque" name=fc667 profile=\
    FCOM-2_8M service=pppoe
add caller-id=08:40:F3:6C:04:5F comment=Rupa name=fc668 profile=FCOM-1_5M \
    service=pppoe
add caller-id=08:40:F3:7A:54:B7 comment="Gulam camdani" name=fc669 profile=\
    FCOM-1_5M service=pppoe
add comment="\tChaina Akter" name=fc473 profile=FCOM-1_5M service=pppoe
add caller-id=04:95:E6:6E:60:10 comment=Puton name=fc671 profile=FCOM-1_5M \
    service=pppoe
add caller-id=D8:32:14:A4:18:EF comment="Atiq miya" name=fc672 profile=\
    FCOM-1_5M service=pppoe
add caller-id=8C:90:2D:AD:B2:64 comment="Hridoy miya" name=sp1033 profile=\
    FCOM-1_5M service=pppoe
add caller-id=A8:29:48:6C:99:05 comment=Tanvhir name=sp1034 profile=FCOM-1_5M \
    service=pppoe
add caller-id=3C:78:95:31:65:5C name=raju80 profile=FCOM-1_5M service=pppoe
add caller-id=30:16:9D:EE:9B:6C name=raju81 profile=FCOM-1_5M service=pppoe
add caller-id=EC:75:0C:52:C9:16 comment=Sopan name=fc674 profile=FCOM-1_5M \
    service=pppoe
add caller-id=E0:D3:62:E4:24:D7 name=op03 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:29:C3:99 name=op08 profile=FCOM-1_5M service=pppoe
add caller-id=60:83:E7:40:F8:8D comment="\tTaju miya" name=sp1035 profile=\
    FCOM-2_8M service=pppoe
add caller-id=D8:32:14:A4:1C:27 comment="Kawser [Home]" name=hk1011 profile=\
    FCOM-1_5M service=pppoe
add caller-id=CC:2D:21:E7:8C:38 comment=Kawser name=hk1012 profile=FCOM-2_8M \
    service=pppoe
add caller-id=50:0F:F5:D8:60:08 comment="Saidul Mia" name=hk1013 profile=\
    FCOM-2_8M service=pppoe
add caller-id=D8:0D:17:DC:21:C5 comment="Azad " name=fc675 profile=FCOM-1_5M \
    service=pppoe
add caller-id=3C:CD:57:94:80:B8 comment="Anamul Hoque " name=fc676 profile=\
    FCOM-1_5M service=pppoe
add name=sp1036 profile=FCOM-1_5M service=pppoe
add comment="Anowar islam" name=fc487 profile=FCOM-1_5M service=pppoe
add caller-id=DA:44:89:DF:E7:AF name=op02 profile=FCOM-1_5M service=pppoe
add caller-id=50:0F:F5:B2:77:A8 comment=Dilower name=fc237 profile=FCOM-1_5M \
    service=pppoe
add comment="Ali Ahmed" name=hk1015 profile=FCOM-2_8M service=pppoe
add caller-id=0C:80:63:81:D5:0F comment="Shamim master " name=fc677 profile=\
    FCOM-1_5M service=pppoe
add caller-id=08:40:F3:B4:85:59 comment=Masum name=fc678 profile=FCOM-1_5M \
    service=pppoe
add caller-id=B8:3A:08:7C:05:A9 comment="\tMehedi" name=fc679 profile=\
    FCOM-1_5M service=pppoe
add caller-id=04:BA:D6:10:48:D7 comment=Payel name=fc680 profile=FCOM-2_8M \
    service=pppoe
add caller-id=50:0F:F5:D7:F9:C0 comment="Helena " name=fc681 profile=\
    FCOM-1_5M service=pppoe
add caller-id=3C:52:A1:76:AD:67 name=raju51 profile=FCOM-1_5M service=pppoe
add caller-id=8C:86:DD:86:3F:B3 comment=" ()  " name=fc682 profile=FCOM-3_12M \
    service=pppoe
add caller-id=08:40:F3:E2:3E:08 comment=Sagor name=fc683 profile=FCOM-1_5M \
    service=pppoe
add caller-id=08:40:F3:B2:22:BF comment=Harun name=fc684 profile=FCOM-1_5M \
    service=pppoe
add caller-id=D8:32:14:A5:A1:E0 name=raju28 profile=FCOM-2_8M service=pppoe
add caller-id=08:40:F3:B2:22:DF comment="Abu sakor" name=fc685 profile=\
    FCOM-1_5M service=pppoe
add caller-id=3C:6A:D2:1B:4E:6D comment="Gupal kormokar" name=fc686 profile=\
    FCOM-1_5M service=pppoe
add caller-id=50:0F:F5:D5:FD:1C comment=Goni name=fc687 profile=FCOM-1_5M \
    service=pppoe
add caller-id=3C:78:95:31:65:50 comment=Safayet name=sp1037 profile=FCOM-1_5M \
    service=pppoe
add caller-id=84:D8:1B:07:08:F1 name=sp1039 profile=FCOM-1_5M service=pppoe
add caller-id=08:40:F3:B2:22:47 name=sp1038 profile=FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:10:B3:DF comment="Babul Sarker" name=fc688 profile=\
    FCOM-1_5M service=pppoe
add caller-id=08:40:F3:DE:F6:A9 comment=Taju name=fc690 profile=FCOM-1_5M \
    service=pppoe
add caller-id=D8:32:14:73:3D:C7 comment=Rakib name=fc691 profile=FCOM-1_5M \
    service=pppoe
add caller-id=08:40:F3:94:DE:37 comment="Tayba afrin/holud miya" name=fc692 \
    profile=FCOM-1_5M service=pppoe
add caller-id=CC:BA:BD:94:D0:05 comment="Al mamun" name=fc693 profile=\
    FCOM-1_5M service=pppoe
add caller-id=3C:78:95:32:33:4B comment=Tasfiq name=fc694 profile=FCOM-1_5M \
    service=pppoe
add caller-id=50:3D:D1:3B:90:DD comment="Tariqul Islam" name=fc271 profile=\
    FCOM-1_5M service=pppoe
add caller-id=98:C7:A4:2B:CB:77 comment=Kaiyum name=sp1040 profile=FCOM-1_5M \
    service=pppoe
add caller-id=3C:78:95:32:69:60 name=raju30 profile=FCOM-1_5M service=pppoe
add caller-id=CC:2D:21:6C:14:E8 name=sp1041 profile=FCOM-1_5M service=pppoe
add caller-id=10:5A:95:5F:FA:45 name=raju53 profile=FCOM-3_12M service=pppoe
add caller-id=CC:2D:21:EA:58:98 comment="Akhi akther" name=fc695 profile=\
    FCOM-1_5M service=pppoe
add caller-id=08:40:F3:7A:54:F7 comment=Arif name=fc696 profile=FCOM-1_5M \
    service=pppoe
add caller-id=3C:78:95:31:61:A8 comment=Momin name=fc697 profile=FCOM-1_5M \
    service=pppoe
add caller-id=08:40:F3:DE:05:89 comment=Hridoy name=fc698 profile=FCOM-1_5M \
    service=pppoe
add caller-id=0C:80:63:B7:19:F1 comment="Md Bilal" name=fc699 profile=\
    FCOM-1_5M service=pppoe
add caller-id=D8:32:14:8C:51:E8 name=raju48 profile=FCOM-1_5M service=pppoe
add caller-id=BC:E0:01:49:B7:E0 comment="Safi ullah" name=fc82 profile=\
    FCOM-1_5M service=pppoe
add caller-id=08:40:F3:95:88:F7 comment="Shanam baby" name=fc700 profile=\
    FCOM-1_5M service=pppoe
add name=sp1042 profile=FCOM-1_5M service=pppoe
add caller-id=08:40:F3:7A:55:3F name=sp1043 profile=FCOM-1_5M service=pppoe
add caller-id=10:5A:95:60:09:89 comment=Didar name=fc701 profile=FCOM-1_5M \
    service=pppoe
add comment=Jahangir name=fc702 profile=FCOM-2_8M service=pppoe
add caller-id=08:40:F3:94:DD:EF comment=anwar name=fc703 profile=FCOM-1_5M \
    service=pppoe
add caller-id=08:40:F3:95:99:E7 comment=Sojol name=fc704 profile=FCOM-1_5M \
    service=pppoe
add caller-id=CC:2D:21:15:D5:57 comment=Alamin name=hk1016 profile=FCOM-2_8M \
    service=pppoe
add caller-id=50:0F:F5:83:01:3F name=op12 profile=FCOM-2_8M service=pppoe
add caller-id=98:BA:5F:A8:BC:2F comment="Pondob bishay sharma" name=fc705 \
    profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:15:A0:30 comment="Bkash Aditta" name=fc706 profile=\
    FCOM-1_5M service=pppoe
add caller-id=08:40:F3:95:99:BF comment="Tuhin Master" name=fc707 profile=\
    FCOM-1_5M service=pppoe
add caller-id=CC:2D:21:31:64:F0 comment="Smart library and computure" name=\
    fc708 profile=FCOM-1_5M service=pppoe
add comment=Oliullah name=fc709 profile=FCOM-2_8M service=pppoe
add caller-id=4C:D7:C8:7E:AB:78 disabled=yes name=raju63 profile=FCOM-1_5M \
    service=pppoe
add caller-id=BC:62:CE:01:10:91 name=raju82 profile=FCOM-1_5M service=pppoe
add caller-id=08:40:F3:E0:EB:88 comment=Keni name=sp1044 profile=FCOM-1_5M \
    service=pppoe
add caller-id=DC:8E:8D:4B:E1:8E comment=Naim name=fc710 profile=FCOM-1_5M \
    service=pppoe
add caller-id=EC:75:0C:AD:1C:4D name=fc711 profile=FCOM-1_5M service=pppoe
add comment="Abu Sayed (Somon)" name=fc408 profile=FCOM-1_5M service=pppoe
add caller-id=3C:78:95:C0:9C:11 name=raju83 profile=FCOM-3_12M service=pppoe
add comment=Mezhba name=fc181 profile=FCOM-2_8M service=pppoe
add caller-id=60:83:E7:61:7D:0D name=raju37 profile=FCOM-1_5M service=pppoe
add comment="Lutfa Begum/Monir" name=fc417 profile=FCOM-1_5M service=pppoe
add comment="Alam chairman " name=sp1045 profile=FCOM-1_5M service=pppoe
add comment="Sujat " name=sp1046 profile=FCOM-1_5M service=pppoe
add caller-id=BC:22:28:C0:6A:31 comment="Jakir hosen" name=fc715 profile=\
    FCOM-3_12M service=pppoe
add caller-id=14:4D:67:30:1D:71 comment=Priya name=fc716 profile=FCOM-1_5M \
    service=pppoe
add caller-id=30:07:5C:23:FD:75 comment=Hridoy name=fc714 profile=FCOM-1_5M \
    service=pppoe
add caller-id=4C:ED:FB:D1:52:80 comment=ibrahim name=hk1017 profile=FCOM-1_5M \
    service=pppoe
add caller-id=80:9F:E4:08:FC:A8 comment=Sahalom name=fc717 profile=FCOM-1_5M \
    service=pppoe
add comment="Kobir (madan thana)" name=fc307 profile=FCOM-1_5M service=pppoe
add comment=Jamir name=fc170 profile=FCOM-3_12M service=pppoe
add caller-id=04:95:E6:9F:D9:28 name=raju18 profile=FCOM-1_5M service=pppoe
add comment=Salman name=sp1004 profile=FCOM-1_5M service=pppoe
add comment="Rajan mia" name=fc498 profile=FCOM-1_5M service=pppoe
add caller-id=D8:32:14:77:06:D0 name=raju84 profile=FCOM-1_5M service=pppoe
add comment=Nachima name=fc718 profile=FCOM-1_5M service=pppoe
add name=op14 profile=FCOM-1_5M service=pppoe
add comment="Zakiya akther" name=fc719 profile=FCOM-1_5M service=pppoe
add comment="Momin islam" name=fc720 profile=FCOM-1_5M service=pppoe
add caller-id=08:40:F3:A6:9D:E9 name=sp1048 profile=FCOM-1_5M service=pppoe
add comment="Ariful islam " name=fc721 profile=FCOM-1_5M service=pppoe
add comment="Dr.Proma Sharma" name=fc722 profile=FCOM-1_5M service=pppoe
add name=raju02 profile=FCOM-1_5M service=pppoe
add comment=Washim name=fc723 profile=FCOM-1_5M service=pppoe
add comment="MD ZOBAIR HOSSEN SOTON" name=fc724 profile=FCOM-1_5M service=\
    pppoe
add comment="Anisul Hoque " name=fc290 profile=FCOM-2_8M service=pppoe
add comment=Jashim name=fc622 profile=FCOM-1_5M service=pppoe
add comment=Bishyajit name=fc730 profile=FCOM-2_8M service=pppoe
add comment="Mitali Paul" name=fc235 profile=FCOM-2_8M service=pppoe
add comment=Naim name=fc725 profile=FCOM-1_5M service=pppoe
add comment=Ronjit name=fc726 profile=FCOM-1_5M service=pppoe
add comment=Obaydul name=fc727 profile=FCOM-2_8M service=pppoe
add comment="Baharul islam" name=fc728 profile=FCOM-1_5M service=pppoe
add comment="manik devnat" name=fc729 profile=FCOM-1_5M service=pppoe
add name=op15 profile=FCOM-2_8M service=pppoe
add name=raju@ profile=FCOM-2_8M service=pppoe
add comment=Ontar name=fc526 profile=FCOM-1_5M service=pppoe
add comment="Selina Akter" name=fc238 profile=FCOM-1_5M service=pppoe
add comment="Amirul islam " name=fc12 profile=FCOM-1_5M service=pppoe
add name=fc588 profile=FCOM-1_5M service=pppoe
add comment="Sadaf Home" name=fc360 profile=FCOM-1_5M service=pppoe
add comment=Rumon name=fc731 profile=FCOM-1_5M service=pppoe
add comment="\t" name=fc458 profile=FCOM-4_20M service=pppoe
add name=fc112 profile=FCOM-7_40M service=pppoe
add comment="Sultana Akter" name=fc148 profile=FCOM-1_5M service=pppoe
add name=sp1050 profile=FCOM-1_5M service=pppoe
add name=sp1049 profile=FCOM-1_5M service=pppoe
add name=sp1051 profile=FCOM-1_5M service=pppoe
add comment="Ali hamja" name=fc733 profile=FCOM-3_12M service=pppoe
add name=fc474 profile=FCOM-1_5M service=pppoe
add comment="Rosher misty" name=fc732 profile=FCOM-2_8M service=pppoe
add name=raju85 profile=FCOM-1_5M service=pppoe
add comment=Sakib name=fc734 profile=FCOM-1_5M service=pppoe
add comment=Nadim name=fc735 profile=FCOM-1_5M service=pppoe
add comment=Juwel name=fc736 profile=FCOM-1_5M service=pppoe
add comment=Shuhel name=fc739 profile=FCOM-1_5M service=pppoe
add comment=Imamul name=sp1052 profile=FCOM-1_5M service=pppoe
add name=sp1053 profile=FCOM-1_5M service=pppoe
add comment="Dayal Das" name=fc737 profile=FCOM-2_8M service=pppoe
add comment=Mobin name=fc738 profile=FCOM-1_5M service=pppoe
add name=sp1054 profile=FCOM-1_5M service=pppoe
add comment="Md nurul islam master " name=fc740 profile=FCOM-1_5M service=\
    pppoe
add comment="Azharul islam" name=fc741 profile=FCOM-1_5M service=pppoe
add comment=Rupok name=sp1056 profile=FCOM-1_5M service=pppoe
add comment="F.K telecom " name=fc742 profile=FCOM-1_5M service=pppoe
add comment=Ferdus name=sp1055 profile=FCOM-1_5M service=pppoe
add comment="Liton miya" name=hk1018 profile=FCOM-1_5M service=pppoe
add comment=Jakir, name=fc743 profile=FCOM-1_5M service=pppoe
add comment="Hafeez somrat" name=fc744 profile=FCOM-1_5M service=pppoe
add name=op13 profile=FCOM-1_5M service=pppoe
add name=sp1057 profile=FCOM-1_5M service=pppoe
add comment=Jakir name=fc747 profile=FCOM-1_5M service=pppoe
add comment=Showrab name=fc745 profile=FCOM-1_5M service=pppoe
add comment="Bulbul ahmed" name=sp1058 profile=FCOM-3_12M service=pppoe
add comment="Md Riyad" name=fc746 profile=FCOM-1_5M service=pppoe
add comment=bablu name=fc748 profile=FCOM-4_20M service=pppoe
add comment=Mahaphug name=fc520 profile=FCOM-1_5M service=pppoe
add comment="Awlad Hossen" name=fc593 profile=FCOM-1_5M service=pppoe
add comment="Raihan alam" name=fc749 profile=FCOM-1_5M service=pppoe
add comment=Klanto name=fc750 profile=FCOM-1_5M service=pppoe
add comment="Joy " name=fc751 profile=FCOM-1_5M service=pppoe
add name=op05 profile=FCOM-1_5M service=pppoe
add name=fc752 profile=FCOM-2_8M service=pppoe
add name=hk1004 profile=FCOM-1_5M service=pppoe
add comment=Ashraful name=fc689 profile=FCOM-1_5M service=pppoe
add name=fc753 profile=FCOM-1_5M service=pppoe
add comment="\tMd. Muzammel Haque" name=fc389 profile=FCOM-1_5M service=pppoe
add comment=Arif name=fc432 profile=FCOM-1_5M service=pppoe
add name=fc754 profile=FCOM-2_8M service=pppoe
add name=fc755 profile=FCOM-1_5M service=pppoe
add name=fc756 profile=FCOM-1_5M service=pppoe
add name=fc757 profile=FCOM-1_5M service=pppoe
add name=fc758 profile=FCOM-1_5M service=pppoe
add name=fc759 profile=FCOM-1_5M service=pppoe
add name=fc760 profile=FCOM-1_5M service=pppoe
/routing bgp connection
add as=64512 disabled=no input.filter=Bdix_In local.role=ebgp name=Bdix_Scom \
    remote.address=10.31.54.33/32 .as=58717 routing-table=main
add as=64513 disabled=no local.address=103.96.71.79 .role=ebgp name=CDN \
    remote.address=103.96.71.78/32 .as=58717 routing-table=main
add as=64512 disabled=yes local.address=10.10.250.2 .role=ebgp name=\
    Tunnel_Bdix output.filter-chain=Tunnel_Out_Filter remote.address=\
    10.10.250.1/32 .as=64515 router-id=10.10.250.2 routing-table=main
add as=64513 disabled=no local.role=ebgp name=GGC remote.address=\
    10.10.101.233/32 .as=58717 routing-table=main
/routing filter rule
add chain=Cdn_Accept disabled=no rule="if (dst==154.85.74.0/24) {accept}"
add chain=Cdn_Accept disabled=no rule="if (dst==154.85.65.0/24) {accept}"
add chain=Cdn_Accept disabled=no rule="if (dst==157.119.185.128/30) {accept}"
add chain=Cdn_Accept disabled=no rule="if (dst==157.119.185.132/30) {accept}"
add chain=Cdn_Accept disabled=no rule="if (dst==157.119.92.0/22) {accept}"
add chain=Cdn_Accept disabled=no rule=\
    "if (dst in 104.16.0.0/12 && dst-len in 12-24) {accept}"
add chain=Cdn_Accept disabled=no rule=reject
add chain=Bdix_In rule=\
    "if (dst in 114.130.116.0/22 && dst-len in 22-24) {reject}"
add chain=Bdix_In disabled=no rule=\
    "if (dst in 180.211.213.0/24 && dst-len in 24) {reject}"
add chain=Bdix_In disabled=no rule=\
    "if (dst in 103.48.16.0/22 && dst-len in 22-24) {reject}"
add chain=Bdix_In disabled=no rule=\
    "if (dst in 114.130.240.0/21 && dst-len in 21-24) {reject}"
add chain=Bdix_In rule=\
    "if (dst in 123.49.0.0/18 && dst-len in 18-24) {reject}"
add chain=Bdix_In disabled=no rule=\
    "if (dst in 131.186.48.0/22 && dst-len in 22-24) {reject}"
add chain=Bdix_In disabled=no rule=\
    "if (dst in 180.211.128.0/17 && dst-len in 17-24) {reject}"
add chain=Bdix_In rule=accept
add chain=Tunnel_IN_Filter disabled=no rule="set distance 1"
add chain=Tunnel_IN_Filter disabled=no rule=accept
add chain=Tunnel_Out_Filter disabled=no rule=reject
/snmp
set enabled=yes
/system clock
set time-zone-name=Asia/Dhaka
/system identity
set name=Core_Router
/system logging
set 0 topics=info,!firewall
add action=maestrolog topics=firewall
add action=systemlog topics=system
add disabled=yes topics=bgp
add disabled=yes topics=firewall
/system note
set show-at-login=no
/system ntp client
set enabled=yes
/system ntp client servers
add address=0.asia.pool.ntp.org
add address=1.asia.pool.ntp.org
add address=2.asia.pool.ntp.org
add address=3..asia.pool.ntp.org
/system routerboard settings
set enter-setup-on=delete-key
/tool graphing interface
add