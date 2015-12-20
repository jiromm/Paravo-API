import httplib2
import json
import RPi.GPIO as GPIO

# Terminal color
(c_null, c_red, c_green, c_header) = ('\033[0m', '\033[91m', '\033[92m', '\033[95m')
pin_list = [21, 20, 16, 26, 19, 13, 6, 5]

def init():
    print(c_header + 'initiated...' + c_null)

    config = get_config()
    (status, device) = get_device(config['device_id'])

    config_GPIO()

    if status:
        print(c_header + '{0} found and it\'s {1}'.format(device['name'], device['status']) + c_null)

        for port in device['ports']:
            setup_GPIO(port, device['ports'][port]['status'])
    else:
        print(c_red + 'Error occured' + c_null)

def setup_GPIO(port_id, status):
    port_id = pin_list[int(port_id) - 1]

    if str(status) == 'active':
        GPIO.setup(port_id, GPIO.OUT, initial=GPIO.LOW)
    else:
        GPIO.setup(port_id, GPIO.OUT, initial=GPIO.HIGH)

def get_device(device_id):
    status = False;
    data = None;

    try:
        h = httplib2.Http()
        (response, content) = h.request('http://iot.jiromm.com/devices/%s' % device_id, headers={
            'Content-type': 'application/json'
        })

        json_string = content.decode('utf-8')
        json_object = json.loads(json_string)

        if (json_object['status'] == 'success'):
            data = json_object['data']
            status = True
        else:
            raise RuntimeError
    except RuntimeError:
        print('Server Side Problem')
    except:
        print('Something Went Wrong')
    finally:
        return (status, data)

def get_config():
    return {
        'device_id': 1
    }

def config_GPIO():
    GPIO.setmode(GPIO.BCM)
    GPIO.setwarnings(False)

if __name__ == '__main__':
    init()