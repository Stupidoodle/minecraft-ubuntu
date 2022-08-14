import requests
import threading

def do_request():
    while True:
        r = requests.get("http://172.28.215.222/minecraft.php?run=true")
        print(r)
threads = []

for i in range(50):
    t = threading.Thread(target=do_request)
    t.daemon = True
    threads.append(t)

for i in range(50):
    threads[i].start()

for i in range(50):
    threads[i].join()