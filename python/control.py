import httplib2
import json

def init():
    print('initiated...')

    config = get_config()
    (status, device) = get_device(config['device_id'])

    if status:
        print('{0} found and it\'s {1}'.format(device['name'], 'active' if int(device['status']) == 1 else 'inactive'))
    else:
        print('Error occured')
    # print(status, devices)

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

if __name__ == '__main__':
    init()