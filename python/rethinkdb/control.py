import config
import json
import RPi.GPIO as GPIO
import rethinkdb as r

# Terminal color
(c_null, c_red, c_green, c_header) = ('\033[0m', '\033[91m', '\033[94m', '\033[95m')

# Relay mapping
pin_list = [21, 20, 16, 26, 19, 13, 6, 5]

def init():
    print(c_header + 'initiated...' + c_null)

    # Setup GPIO ports
    config_GPIO()

    # Configure RethinkDB
    config_RethinkDB()

    (status, data) = get_device_info_from_db(config.device_id)

    if (status):
        print(c_header + '{0} found'.format(data['name']) + c_null)

        processor(data)
        listen_for_changes(config.device_id)

def message_preparator(message):
    status = True
    data = None

    return (status, message)

def processor(device):
    for port in device['ports']:
        setup_GPIO(port, device['ports'][port]['status'])

def listen_for_changes(device_id):
    cursor = r.table("devices").get(device_id).changes().run()

    if (cursor):
        for document in cursor:
            processor(document['new_val'])

def setup_GPIO(port_id, status):
    port_id = pin_list[int(port_id) - 1]

    if str(status) == 'active':
        GPIO.setup(port_id, GPIO.OUT, initial=GPIO.LOW)
    else:
        GPIO.setup(port_id, GPIO.OUT, initial=GPIO.HIGH)

def get_device_info_from_db(device_id):
    print(c_header + 'Getting data from db' + c_null)

    status = False;
    data = None;

    try:
        status = True
        data = r.table("devices").get(device_id).run()
    except RuntimeError:
        print('Server Side Problem')
    except:
        print('Something Went Wrong')
    finally:
        return (status, data)

def config_GPIO():
    GPIO.setmode(GPIO.BCM)
    GPIO.setwarnings(False)

def config_RethinkDB():
    r.connect(host=config.host, port=config.port, db=config.db, auth_key=config.auth_key).repl()

if __name__ == '__main__':
    try:
        init()
    except KeyboardInterrupt:
        # Reset GPIO settings
        GPIO.cleanup()

        print('Well done. Have a rest!')