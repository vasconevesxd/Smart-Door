import RPi.GPIO as GPIO
import time

GPIO.setmode(GPIO.BCM)

GPIO.setup(3, GPIO.OUT)

p=GPIO.PWM(3,50)
p.start(7.5)

try:
    p.ChangeDutyCycle(7.5)
    time.sleep(1)
    p.ChangeDutyCycle(12.5)
    time.sleep(1)

except KeyboardInterrupt:
    print("\nProgram end form user.")
except:
    print("\nError!!!")
finally:
    p.stop()
    GPIO.cleanup()