#importar todos os pacotes necessarios
from imutils.video import VideoStream
from imutils.video import FPS
import numpy as np
import argparse
import imutils
import time
import cv2


ap = argparse.ArgumentParser()
ap.add_argument("-p", "--prototxt", required=True,
	help="path to Caffe 'deploy' prototxt file")
ap.add_argument("-m", "--model", required=True,
	help="path to Caffe pre-trained model")
ap.add_argument("-c", "--confidence", type=float, default=0.2,
	help="minimum probability to filter weak detections")
args = vars(ap.parse_args())

# inicializa a lista de classes do MobileNet SSD que foi treinada para detetar objetos, e de seguida gera uma caixa para cada classe.
CLASSES = ["background", "aeroplane", "bicycle", "bird", "boat",
	"bottle", "bus", "car", "cat", "chair", "cow", "diningtable",
	"dog", "horse", "motorbike", "person", "pottedplant", "sheep",
	"sofa", "train", "tvmonitor"]
COLORS = np.random.uniform(0, 255, size=(len(CLASSES), 3))

#correr o modelo de rede neural 
print("[INFO] loading model...")
net = cv2.dnn.readNetFromCaffe(args["prototxt"], args["model"])

#Iniciar o video  stream, permite o sensor da camera "aquecer" e inicializa o contador de FPS
# vs = VideoStream(usePiCamera=True).start()
print("[INFO] starting video stream...")
vs = VideoStream(src=0).start()

time.sleep(2.0)
fps = FPS().start()


while True:
	#redimensiona a janela para o maximo de 400 largura
	frame = vs.read()
	frame = imutils.resize(frame, width=400)

	#redimensiona a janela depois converte para um blob e depois passa o blob pela network 
	#neural e obtem a "detections" e uma caixa de privisao

	(h, w) = frame.shape[:2]
	blob = cv2.dnn.blobFromImage(cv2.resize(frame, (300, 300)),
		0.007843, (300, 300), 127.5)


	net.setInput(blob)
	detections = net.forward()

	#o loop serve para mostrar o que esta a ser detectado pela camera em tempo real
	for i in np.arange(0, detections.shape[2]):
		#associa a probabilidade com a previsao
		confidence = detections[0, 0, i, 2]

		#vai filtar as detecoes fracas assegurando que a "confidence"
		#e maior que o minimo da "confidence"
		if confidence > args["confidence"]:
			#extrai a class da detecao e depois ajusta as cordenadas da caixa de detecao do objeto
			idx = int(detections[0, 0, i, 1])
			box = detections[0, 0, i, 3:7] * np.array([w, h, w, h])
			(startX, startY, endX, endY) = box.astype("int")

			#escreve as previsoes na janela
			label = "{}: {:.2f}%".format(CLASSES[idx],
				confidence * 100)
			cv2.rectangle(frame, (startX, startY), (endX, endY),
				COLORS[idx], 2)
			y = startY - 15 if startY - 15 > 15 else startY + 15
			cv2.putText(frame, label, (startX, y),
				cv2.FONT_HERSHEY_SIMPLEX, 0.5, COLORS[idx], 2)

	#mostra o resultado
	cv2.imshow("Frame", frame)
	key = cv2.waitKey(1) & 0xFF


	#if key == ord("q"):
		#break

	#verifica que quando aparecer a class "person" ele vai guardar num ficheiro de texto
	#o valor Human e executar o script do led e o servo motor 	
	if idx == 15:
		file = open("object_type_value.txt","w") 
		file.write("Human") 
		file.close()  
		execfile('api/object_detection/led.py')
		execfile('api/object_detection/servo.py')
		break
	#atualiza o contador de fps
	fps.update()

#mostra os fps e a ultima classe que detectou
fps.stop()

print("[INFO] elapsed time: {:.2f}".format(fps.elapsed()))
print("[INFO] approx. FPS: {:.2f}".format(fps.fps()))
print("[INFO] type: {}".format(CLASSES[idx]))
#fecha o programa por completo
cv2.destroyAllWindows()
vs.stop()

