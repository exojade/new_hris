# -*- coding: utf-8 -*-
import sys
sys.path.append("zk")


ip_address = sys.argv[1]
file_name = sys.argv[2]
password = sys.argv[3]


from zk import ZK, const

conn = None
zk = ZK(ip_address, port=4370, timeout=5, password=0, force_udp=False, ommit_ping=False)
try:
    print ('Connecting to device ...')
    conn = zk.connect()
    print ('Disabling device ...')
    conn.disable_device()
    print ('Firmware Version: : {}'.format(conn.get_firmware_version()))
    # print '--- Get User ---'
    attendance = conn.get_attendance()
    filename = "file_folder/attlogs/" + file_name;
    f = open(filename, "w")
    i = 0
    for att in attendance:
        i += 1
       
        f.write(" {:>2}\t{} {}\t{}\t{}\t{}".format(att.user_id, att.timestamp, att.status, att.punch, '0', '0'))
        f.write("\n")
        # print ("ATT {:>6}: uid:{:>3}, user_id:{:>8} t: {}, s:{} p:{}".format(i, att.uid, att.user_id, att.timestamp, att.status, att.punch))
    f.close()
    # print ('success')
    conn.enable_device()
except Exception as e:
    # print ("error")
    print ("Process terminate : {}".format(e))
finally:
    if conn:
        conn.disconnect()
