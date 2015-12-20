import httplib2
import json

h = httplib2.Http()
(response, content) = h.request('http://iot.jiromm.com/devices', headers={
    'Content-type': 'application/json'
})

json_string = content.decode('utf-8')
json_object = json.loads(json_string)

print(json_object['status'])
