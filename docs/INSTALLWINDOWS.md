#### Inhaltsverzeichnis
- [Docker & docker-compose](#docker)
- [GIT & Node](#git)
- [Composer](#composer)
- [Klonen der Repo](#repo)
- [Container erstellen](#container)
    - [Datenbank herunterladen](#loaddb)
    - [Datenbank erstellen & importieren](#importdb)
- [Wordpress Repo einrichten](#wordpress)
- [Git Flow einrichten](#gitflow)

[Vorbereitung Windows](./prepwindows.md)


<a name="docker"/>

## Docker & docker-compose  
Nun muss noch in der Linux-Distro Docker und Docker-Compose installiert werden.  
Dazu die Distribution starten und folgende Befehle in der Shell ausführen.  

    # Update the apt package list.
    sudo apt-get update -y

    # Install Docker's package dependencies.
    sudo apt-get install \
        apt-transport-https \
        ca-certificates \
        curl \
        gnupg-agent \
        php-xml \
        software-properties-common

    #Hier bitte noch mal gucken welche Distro ihr benutzt. Hier wird der Weg fuer Ubuntu beschrieben.
    # Download and add Docker's official public PGP key.
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -

    # Verify the fingerprint.
    sudo apt-key fingerprint 0EBFCD88
    
    # Add the `stable` channel's Docker upstream repository.
    #
    # If you want to live on the edge, you can change "stable" below to "test" or
    # "nightly". I highly recommend sticking with stable!
    sudo add-apt-repository \
       "deb [arch=amd64] https://download.docker.com/linux/ubuntu \
       $(lsb_release -cs) \
       stable"
    
    # Update the apt package list (for the new apt repo).
    sudo apt-get update -y
    
    # Install the latest version of Docker CE.
    sudo apt-get install -y docker-ce
    
    # Allow your user to access the Docker CLI without needing root access.
    sudo usermod -aG docker $USER


Danach einmal die Shell schließen und eine neue öffnen.  

Als nächstes wird docker-compose installiert.  

    # Install Python and PIP.
    sudo apt-get install -y python python-pip

    # Install Docker Compose into your user's home directory.
    pip install --user docker-compose  

<a name="git"/>    

## Git & Node/Npm

Des weiteren macht es Sinn einige Sachen in der Linux-Distro zu installieren. Zuerst Git. Dazu wieder die Linux Shell öffnen.  

    sudo apt install git
    
Nach dem Installieren das Einrichten von Git nicht vergessen.  Als Beispiel:  

    git config --global user.name "Daniel Ricciardo"
    git config --global user.email "john@doe.com"  
    git config --global core.autocrlf input 
    
In diesem Zuge kann auch direkt der .ssh Ordner im root Verzeichnis erstellt werden.  

    mkdir ~/.ssh
    
Nun wird der Public Key aus dem Windows(falls vorhanden) in das Linux System kopiert. Geht zunächst in euren Benutzer Ordner und kopiert die beiden id_rsa Dateien und fügt sie
in den neu erstellten Ordner auf dem Linux System ein. Am schnellsten gelangt ihr in euer root Verzeichnis über die Adresszeile 
eures Windows Explorer. Dazu tippt ihr ein  

    \\wsl$
    
Nun wählt ihr eure Distro aus. Jetzt können die Dateien in den .ssh Ordner eingefügt werden.

Nun wieder die Shell öffnen und node installieren.  

    sudo apt install nodejs
    sudo apt install npm
    
Zum überprüfen der Installation  

    node -v
    npm -v 
    
ausführen.  
  

<a name="composer"/>

## Composer installieren
Am schnellsten geht es wenn ihr danach googlet. Z.B. nach composer installieren ubuntu 18.04

ansonsten diese Befehle ausführen.

    # Dependencies installieren
    sudo apt update
    sudo apt install wget php-cli php-zip unzip


    cd ~
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    
    HASH="$(wget -q -O - https://composer.github.io/installer.sig)"
    
    php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
    
    #Output sollte Installer verified sein.

    sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

danach einmal composer in den terminal eingeben um zu überprüfen, ob die Installation funktioniert hat.


<a name="Repo"/>

## Klonen der Repo

Nun kann mit der Installation der Entwicklungsumbgebung beginnen. Zunächst wird das Repo geklont. Hierzu entweder in das root Verzeichnis gehen  

    cd ~

oder in den Benutzer Ordner

    cd /home/<benutzer-name>
    
dann den Befehl zum Klonen ausführen.

    git clone git@gitlab.com:mindsquare/dev.git  

sollte eine Fehlermeldung erscheinen, dass euer Key "too open" ist, dann 
    chmod 600 ~/.ssh/id_rsa
ausführen.
    

<a name="container"/>

## Container erstellen  

Zunächst einmal sicherstellen, dass Docker keine Credentials für euch gesetzt hat.

    nano ~/.docker/config.json
    # sollte bei euch im credstore etwas stehen, das bitte entfernen

Als nächstes in das Verzeichnis gehen  

    cd dev


Stellt sicher das Docker Desktop läuft.
Um die Datenbank initial einzurichten. Führe 

    docker run -v /root/dev/data/mysql/:/var/lib/mysql/ -e MYSQL_ALLOW_EMPTY_PASSWORD="yes" -e MYSQL_ROOT_HOST="%"  mariadb:10.0.38 

aus. Danach könnt ihr die Docker Images builden.

    docker-compose up --build
    
ausführen. Dies kann einige Minuten dauern.  

<a name="loaddb"/>  

### Datenbank herunterladen
In der Zwischenzeit kann eine Datenbank vom maxcluster heruntergeladen werden. 
Das ist eine Schritt für Schritt Anleitung, wie die Datenbank vom Live-Server heruntergeladen werden kann.  

Verbindet euch über ssh mit dem Server

    ssh web-user@185.88.213.222

Wechselt in den database Ordner.

    cd restore/database

Erstellt nun einen neuen mysql dump

    mysqldump -u db-user-1 -p --skip-comments --single-transaction --quick db-1 > db1.sql

Das Passwort befindet sich im OneNote. Nun kannst du mit z.B. mit Filezilla die Datenbank herunterladen.
Jetzt könnt ihr mir der Erstellung einer Datenbank anfangen.

<a name="importdb"/>

### Datenbank erstellen & importieren
Zunächst eine Datenbank anlegen.

    #bash im container öffnen
    docker-compose exec php_72 bash
    
    #mysql ausführen
    mysql -u root -p -h db
    
    #Datenbank erstellen
    CREATE DATABASE `mydatabase` CHARACTER SET utf8 COLLATE utf8_general_ci;
    
    # Überprüfen, ob Datenbank angelegt wurde
    show databases;
    
Ist die Datenbank erfolgreich angelegt, kommt ihr mit zweimal exit wieder in den dev Ordner.

Nun die Daten in den Container kopieren.

    docker exec -i dev_db_1 mysql -uroot "Name deiner Datenbank" < /pfad/zur/datenbank.sql
    
    #Alternative, wenn der obere Befehl nicht funktioniert
    #Client installieren
    apt install mariadb-client-core-10.1
    
    # Datenbank einstellen
    mysql -h 127.0.0.1 -u root -p <datenbank-name> < datenbank-file.sql
    
Somit sind die Daten in der Datenbank. Als nächsten wird das Wordpress Repo installiert.  

<a name="wordpress"/>

## Wordpress Repo einrichten

Zunächst wieder in den dev Ordner wechseln.  

    cd ~/dev
    
oder 

    cd /home/benutzer-name/dev
    
Als erstes wird das Repo als Submodule eingerichtet.  

    git submodule init
    
und dann werden die Daten herunter geladen.  

    git submodule update
    
Nach dem Herunterladen der Daten, befindt sich das Repo unter

`dev/www/mindsquare-network/htdocs`  


Nun werden die Dateien in die verschiedenen Ordner kopiert, befinden sich im 

/mindsquare-network/install.htdocs und
/freelancercheck/install.website

in den jeweiligen Ordner kopieren.

die hosts Datei ist für das Windowssytem und wird falls noch nicht vorhanden in `C:\Windows\System32\drivers\etc`  eingefügt.  
    
Damit wp-rocket beim ersten Starten der Anwendung die benötigten Dateien beschreiben kann. 
Benötiget das WSL2 noch die richtigen Rechte zum Schreiben der Dateien. Dazu zuerst 

    # in mindsquare-network navigieren
    cd /www/mindsquare-network

    # dann Rechte vergeben
    sudo chmod 777 -R htdocs
    
aus.  
Des weitern muss die wp-config.php angepasst werden. Damit aber die ganze Entwicklungsumgebung in VSCode ist, einmal zurück in den dev Ordner wechseln.

    cd ../..
    
Nun wird vscode im Ordner gestartet.

    code .
    
Jetzt kann die wp-config.php in vscode bearbeitet werden.

Folgende Einstellungen ändern.

- define('DB_NAME', 'db-1'); -> define('DB_NAME', 'euer-datenbank-name');
- define('DB_HOST', 'localhost') -> define('DB_HOST', 'db'); 
- require_once( ABSPATH . 'vendor/autoload.php' );

Nun wieder in den htdocs Ordner wechseln.

Dort zunächst ein 

    composer install

ausführen.

um die Komponenten zu laden, wird 

    composer dump-autoload -o

ausgeführt. Als nächstes wird die package-lock.json gelöscht.

Nach dem löschen der Datei ein 

    npm install
    npm run css-all

Sollte Gulp global noch nicht auf eurem System installiert sein. Dann einmal

    npm install -g gulp-cli

ausführen. So könnt ihr nun GUlP in der Console verwenden. Jetzt einmal 

    gulp component


ausführen. Jetzt wird in das ms_rz10_nineteen Theme gewechselt.

    cd wp-content/themes/ms_rz10_nineteen

Dort wieder ein

    composer install
    npm install
    npm run build

ausführen.

<a name="gitflow"/>

## GIT FLOW einrichten

    sudo apt update && apt install git-flow

im htdocs Ordner ein 

    git flow init 

ausführen und mit Enter die jeweiligen Einstellungen bestätigen.

<a name="cert"/>

## Zertifikate installieren

Erstellte Zertifikate befinden sich im Ordner ` ~/dev/config/ssl/certs`  
  
Öffnet mit dem Windows Explorer eure Linux Distro

    Windows Taste + E

    #in die Adresszeile

    \\wsl$ 

eingeben. Dann euere Distro auswählen und in den Ordner `~/dev/config/ssl/certs` navigieren.  
Doppelklick auf `cacerts.crt` 

    Zertifikat installieren
    Lokaler Computer aussuchen
    Alle Zertifikate in folgendem Speicher speichern und auf durchsuchen klicken
    Vertrauenswüridige Stammzertifizierungsstellen auswählen
    Weiter drücken und auf Fertig stellen

Danach das mindsquare-network Zertifikat installieren
Doppelklick auf `mindsquare-network.crt`  

    Zertifikat installieren
    Lokaler Computer aussuchen
    Alle Zertifikate in folgendem Speicher speichern und auf durchsuchen klicken
    Eigene Zertifikate auswählen
    Weiter drücken und auf Fertig stellen

Die Installation der Zertifikate ist damit abgeschlossen.  
Benutzt ihr den Firefox Browser müsst ihr in der about:config noch eine Einstellung vornehmen

Dazu den Browser öffnen und in die Adresszeile `about:config` eingeben. Risko akzeptieren

    security.enterprise_roots.enabled

suchen und den Wert auf true setzen. Browser neustarten.sd
 