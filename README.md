Crear client:

php bin/console fos:oauth-server:create-client --redirect-uri="..." --grant-type="..."

generar pdf, docx:
instalar open office

soffice "--accept=socket,host=127.0.0.1,port=2002,tcpNoDelay=1;urp;" --headless --nodefault --nofirststartwizard --nolockcheck --nologo --norestore
sudo apt-get install libreoffice
sudo apt-get install libreoffice-writer
sudo apt-get install python-uno
debian10 es python3-uno

https://devhub.io/repos/pwldp-pyodconverter
https://raw.githubusercontent.com/veltzer/linuxapi/master/scripts/DocumentConverter.py
# pr
