# -*- coding: utf-8 -*-
import sys
sys.path.append("zk")

from zk import ZK, const

conn = None
zk = ZK('192.168.12.2', port=4370, timeout=5, password=0, force_udp=False, ommit_ping=False)
try:
    print ('Connecting to device ...')
    conn = zk.connect()
    conn.enable_device()
except Exception as e:
    print ("Process terminate : {}".format(e))
finally:
    if conn:
        conn.disconnect()
