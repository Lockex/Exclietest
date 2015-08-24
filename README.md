# Exclie prueba

Si no esta instalado composer.
```sh
php -r "readfile('https://getcomposer.org/installer');" | php
```
Nos vamos a instalar zend framework en la carpeta del proyecto.

```sh
composer create-project -n -sdev zendframework/skeleton-application ruta/del/proyecto
```

Opcionalmente podemos crear un host virtual modificando nuestro archivo hosts. 

# Unix/Os X

```sh
$ sudo nano /private/etc/hosts
```
Utilizando las flechas del teclado nos posicionamos al final del texto y agregamos lo siguiente:
```sh
127.0.0.1   Exclietest.rex
```
Despues presionamos Ctrl+O para guardar y despues Ctrl+X para salir del editor.
# Windows
En windows el archivo se encuentra en C:/system32/drivers/etc/hosts y hacemos la misma modificacion, solo que en windows hay que copiar el archivo original de hosts y pegarlo en el escritorio, luego realizamos la modificacion en el archivo copiado, se guarda y se reemplaza por el archivo original.

Luego modificamos nuestro archivo httpd-vhosts.conf usualmente localizado en: apache/conf/extra/httpd-vhosts.conf y agregamos lo siguiente a nuestro archivo.
```sh
<VirtualHost *:80>
     ServerName exclietest.rex
     DocumentRoot ruta/al/proyecto/public
     SetEnv APPLICATION_ENV "development"
     <Directory ruta/al/proyecto/public>
         DirectoryIndex index.php
         AllowOverride All
         Order allow,deny
         Allow from all
     </Directory>
 </VirtualHost>
```

Si no esta instalado bower.
```sh
npm install -g bower
```
Si no esta instalado yeoman. 

```sh
$ npm install -g yo
```

Instalamos globalmente los eskeletos del polymer.

```sh
$ npm install -g generator-polymer
```
Nos vamos a la carpeta del proyecto.

```sh
$ yo polymer
```
Al hacer este ultimo comando ya creamos los archivos del bower que indican los componentes que se instalaron automaticamente gracias al yeoman.
