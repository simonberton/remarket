user-edit-my-profile-form# Docker para Tekman (TkTools y TkCore)

Docker con 3 contenedores para el funcionamiento del ambiente de desarrollo.

- Servidor Web
- Servidor Php
- Servidor Base de datos

Pasos:

1 - Ingresar a carpeta /infra/docker
2 - Correr los siguientes comandos:

```bash
docker-compose up --build
```

Una vez terminado, debemos clonar, vamos a la raiz y clonamos los proyectos.

```bash
cd tkcore
git clone https://youruser@bitbucket.org/tekbooks/tkcore.git ./

cd ../tktools
git clone https://sbertonomitsis@bitbucket.org/tekbooks/new_tktools.git
```

Edita tu archivo de hosts:
```bash
127.0.0.1 local.tkcore.com
127.0.0.1 local.myroom.com
127.0.0.1 local.tktools.com
127.0.0.1 local.pedidos.com
```

Ahora contamos con el docker funcionando local y nuestro código en nuestra máquina.

Debemos configurar los parametros para que funcionen localmente.

TKTOOLS

app/config/parameters.yaml

```bash
parameters:
    database_host: tk-dbserver
    database_port: 3306
    database_name: tktools
    database_user: root
    database_password: password
```

TKCORE
Actualizar
.env:

```bash
TKCORE_DATABASE_URL=mysql://tkcore:tkcore@tk_dbserver:3306/tkcore
TKCORE_API_URL="http://local.tkcore.com/api/"
MYROOM_HOST=local.myroom.com
TKCORE_HOST=local.tkcore.com
ZOHO_DATABASE_HOST=127.0.0.1
ZOHO_DATABASE_PORT=3306
ZOHO_DATABASE_NAME=tkcore_local
ZOHO_DATABASE_USER=root
ZOHO_DATABASE_PSW=tekmanbooks
```

Ahora debemos crear las base de datos:

```bash
docker exec -it tk-dbserver mysql
CREATE DATABASE tktools;
GRANT ALL ON tktools.* TO 'root'@'%' IDENTIFIED BY 'password';
FLUSH PRIVILEGES;
CREATE DATABASE tkcore;
GRANT ALL ON tkcore.* TO 'tkcore'@'%' IDENTIFIED BY 'tkcore';
FLUSH PRIVILEGES;
CREATE DATABASE tkorders;
GRANT ALL ON tkorders.* TO 'root'@'%' IDENTIFIED BY 'password';
FLUSH PRIVILEGES;
```

Acceder a los contenedores:

```bash
docker-exec -it tk-php bash
cd tkcore
composer install
bin/console d:s:u --force
bin/console doctrine:migrations:migrate
cd ../tktools
composer install
bin/console d:s:u --force
bin/console doctrine:migrations:migrate
cd ../tkorders
composer install
bin/console d:s:u --force
bin/console doctrine:migrations:migrate
```

TkCore utiliza webpack para sus assets, para esto debemos instalarlo localmente:

* Yarn

```bash
sudo apt-get install -y nodejs

curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -

echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list

sudo apt-get update && sudo apt-get install -y yarn

export PATH="$PATH:`yarn global bin`"

composer require encore

yarn install 
```

IMPORTANTE:
La configuración de NGINX para los 3 sitios, necesita de un archivo index.php
En tktools, copiar app_dev.php y crear index.php
En tkorders, copiar app_dev.php y crear index.php
Listo !!!
