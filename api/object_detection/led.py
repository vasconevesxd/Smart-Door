import RPi.GPIO as GPIO
import time

in_file = open("object_type_value.txt", "rt") 
contents = in_file.read()         
in_file.close()                   

if (contents == "Human"):
    GPIO.setmode(GPIO.BOARD)

    GPIO.setup(18,GPIO.OUT)

    GPIO.output(18,0)

    GPIO.output(18,1)

    time.sleep(3)

    GPIO.cleanup()
