#!/usr/bin/python
import RPi.GPIO as GPIO
import time

GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)

# General Setup
pinList = [21, 20, 16, 26, 19, 13, 6, 5]
idle = 0.2

# Setup ports
for i in pinList:
    GPIO.setup(i, GPIO.OUT)
    GPIO.output(i, GPIO.HIGH)

# main loop
try:
    for pin in pinList:
        GPIO.output(pin, GPIO.LOW)
        print("Port %s is up" % pin)
        time.sleep(idle)

    pinList.reverse()

    for pin in pinList:
        GPIO.output(pin, GPIO.HIGH)
        print("Port %s is down" % pin)
        time.sleep(idle)

    GPIO.cleanup()
    print('Done!')

    # End program cleanly with keyboard
except KeyboardInterrupt:
  print('Quit')

  # Reset GPIO settings
  GPIO.cleanup()