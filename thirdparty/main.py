import requests
from bs4 import BeautifulSoup
from random import choice
from flask import Flask, request,session,json,jsonify,redirect
import os, time, requests

app = Flask(__name__)
app.config["DEBUG"] = True


def proxy_generator():
    response = requests.get("https://sslproxies.org/")
    soup = BeautifulSoup(response.content, 'html5lib')
    proxy = {'https': choice(list(map(lambda x:x[0]+':'+x[1], list(zip(map(lambda x:x.text, soup.findAll('td')[::8]), map(lambda x:x.text, soup.findAll('td')[1::8]))))))}
    
    return proxy

def data_scraper(request_method, url, **kwargs):
    while True:
        try:
            proxy = proxy_generator()
            print("Proxy currently being used: {}".format(proxy))
            response = requests.request(request_method, url, proxies=proxy, timeout=7, **kwargs)
            break
            # if the request is successful, no exception is raised
        except Exception as e: 
            print(": not working ! Connection error, looking for another proxy", str(e))
            pass
    return response.content



@app.route('/', methods=['GET'])
def home():
    return data_scraper('GET', request.args.get('url'))
app.run()    