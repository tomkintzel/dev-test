## Installation

### Windows

#### Docker

Docker Desktop von https://hub.docker.com/editions/community/docker-ce-desktop-windows herunterladen und installieren.  

Docker starten und in den Einstellungen unter >General>`Enable the experimental WSL 2 based engine` aktiveren.
Danach unter Resources>WSL Integration `Enable integration with my default WSL Distro` aktiveren und die unterhalb die Distribution auswählen, die genutzt wird.  
Damit ist Docker auf der Windows "Seite" eingerichtet.

Nun muss noch in der Linux-Distro Docker und Docker-Compose installiert werden.  
Dazu die Distribution starten und folgende Befehle in der Shell ausführen.  

    # Update the apt package list.
    sudo apt-get update -y

    # Install Docker's package dependencies.
    sudo apt-get install -y \
        apt-transport-https \
        ca-certificates \
        curl \
        software-properties-common

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
    
### Git & Node/Npm

Des weiteren macht es Sinn einige Sachen in der Linux-Distro zu installieren. Zuerst Git. Dazu wieder die Linux Shell öffnen.  

    sudo apt install git
    
Nach dem Installieren das Einrichten von Git nicht vergessen.  Als Beispiel:  

    git config --global user.name "Daniel Ricciardo"
    git config --global user.email "john@doe.com"  
    
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

### Klonen der Repo

Nun kann mit der Installation der Entwicklungsumbgebung beginnen. Zunächst wird das Repo geklont. Hierzu entweder in das root Verzeichnis gehen  

    cd ~

oder in den Benutzer Ordner

    cd /home/<benutzer-name>
    
dann den Befehl zum Klonen ausführen.

    git clone git@gitlab.com:mindsquare/dev.git  
    

### Container erstellen  

Als erstes in das Verzeichenis gehen  

    cd dev

Stellt sicher das Docker Desktop läuft. Dann den Befehl  

    docker-compose up --build
    
ausführen. Dies kann einige Minuten dauern.  
In der Zwischenzeit kann eine Datenbank vom maxcluster heruntergeladen werden.  
Um es möglichst einfach zu machen, die Datenbank im root oder Benutzer Verzeichnis ablegen. Um die Datenbank dann in den Container zu bekommen.

Zunächst eine Datenbank anlegen.

    #bash im container öffnen
    docker-compose exec web bash
    
    #mysql ausführen
    mysql -u root -p -h db
    
    #Datenbank erstellen
    CREATE DATABASE `mydatabase` CHARACTER SET utf8 COLLATE utf8_general_ci;
    
    # Überprüfen, ob Datenbank angelegt wurde
    show databases;
    
Ist die Datenbank erfolgreich angelegt, kommt ihr mit zweimal exit wieder in den dev Ordner.

Nun die Daten in den Container kopieren.

    docker exec -it dev_db_1 mysql -uroot -p db-1 < /pfad/zur/datenbank .sql
    
    #Alternative, wenn der obere Befehl nicht funktioniert
    #Client installieren
    apt install mariadb-client-core-10.1
    
    # Datenbank einstellen
    mysql -h 127.0.0.1 -u root -p <datenbank-name> < datenbank-file.sql
    
Somit sind die Daten in der Datenbank. Als nächsten wird das Wordpress Repo installiert.  

### Wordpress Repo einrichten

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

Als nächstes die Dateien aus der WIKI dieses Repo herunterladen.

Nun werden die Dateien in die verschiedenen Ordner kopiert.

- .htaccess > /htdocs
- wp-config > /htdocs
- index.php > /htdocs/wp-content/blogs.dir (wenn nicht vorhanden dann vorher ein mkdir /wp-content/blogs.dir ausführen)
- wp-rocket-config > /htdocs/wp-content/

die hosts Datei ist für das Windowssytem und wird falls noch nicht vorhanden in `C:\Windows\System32\drivers\etc`  eingefügt.  
Ausserdem muss der Ordner cache mit den unterordnern min und wp-rocket erstellt werden.  

    mkdir /wp-content/cache
    mkdir /wp-content/cache/min
    mkdir /wp-content/cache/wp-rocket
    
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