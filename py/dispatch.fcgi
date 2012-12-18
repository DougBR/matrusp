#!/usr/bin/python
# -*- coding: utf8 -*-

import time, os

def matrufsc_mtime():
    return time.ctime(os.path.getmtime('matrufsc.py'))

import matrufsc
last_mtime = matrufsc_mtime()

def dispatch(environ, start_response):
    global last_mtime
    new_mtime = matrufsc_mtime()
    if last_mtime != new_mtime:
        reload(matrufsc)
        last_mtime = new_mtime
    try:
        return matrufsc.run(environ, start_response)
    except:
        start_response('404 Not Found', [('Content-Type', 'text/html; charset=UTF-8')])
        return ['<html><head><title>404</title></head><body><center>404 - Arquivo não encontrado</center></body></html>']

if __name__ == '__main__':
    from flup.server.fcgi_fork import WSGIServer
    WSGIServer(dispatch).run()
