# reference the ZK Soap PHP namespace
from zksoap import Fingerprint

# initial
machine = Fingerprint('192.168.12.2', '4370', '0')

# get machine status
print("Machine Status : "+machine.getStatus()) # connected | disconnected

print(machine.getAttendance('1')) 
