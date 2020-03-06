# Enwicklungsumgebung Mindsquare

Einfachere Entwicklung mit Docker


Beinhaltet:

- [Vorbereitungen](#vorbereitungen)
- [Installation](#installation)
- [Benutzung](#benutzung)


## Vorbereitungen  

### Windows

Auf einem Windowssystem bietet es sich an auf einem WSL(Windows Subsystem for Linux) zu arbeiten. Daher wird bei der Vorbereitung, auf dieses Setup eingegangen.

#### WSL2 aktivieren
Damit WSL2 genutzt werden kann, müssen einige Vorbereitungen getroffen werden. Zunächst muss überprüft werden welcher Windows-10 Build auf dem Sytem installiert ist.
Dies kann in der cmd durch den Befehl *ver* überprüft werden.

`WSL 2 ist nur in Windows 10-Builds 18917 oder höher verfügbar.` 

Ist ein niedriger Build installiert, so muss sich für das Windows Insider Programm angemeldet werden.
Hierzu auf https://insider.windows.com/de-de/ gehen und die Schritte ausführen.

Als nächstes müssen optionale Komponente aktiviert werden. Dazu folgende Befehle in einer Powershell (als Administrator) ausführen. 

    dism.exe /online /enable-feature /featurename:Microsoft-Windows-Subsystem-Linux /all /norestart  
    dism.exe /online /enable-feature /featurename:VirtualMachinePlatform /all /norestart

Damit die Einstellungen wirksam werden, muss das System einmal neugestartet werden.  

#### Linux-Distribution  
Der nächste Schritt behandelt das Installieren einer Linux Distribution innerhalb des Windowssystem. Es muss also kein Dual Boot eingerichtet werden.

Dazu stellt Windows mittlerweile Linux Distributionen in dem Windows Store bereit. Hier empfehlen wir Ubuntu zu installieren. 
Ubuntu verfügt über eine sehr große Community und vielen Hilfen bei Problemen.  

Also einfach einfach den Windows Store besuchen und eine Distribution auswählen und installieren. Sollte es noch Probleme geben, bitte https://docs.microsoft.com/de-de/windows/wsl/install-win10#install-your-linux-distribution-of-choice besuchen. 

Zum jetztigen Zeitpunkt werden die Distributionen in WSL1 gestartet. Um zu überprüfen welches WSL die Distribution verwendet, bitte in der Powershell  
  
    wsl --list --verbose
    # oder  
    wsl -l -v  
    
eingeben. Ist die Version eurer Distro 2 müsst ihr nichts weiter tun.  
Ansonsten muss die Distro geändert werden. Dazu  

    wsl --set--version <distro-name> 2
    # Beispiel Ubuntu
    wsl --set--version Ubuntu 2 
    
ausführen. Dies kann einige Minuten dauern.  

Sind diese Schritte ausgeführt, ist die Vorbereitung des Windows System abgeschlossen.  

### MacOS  

Hier sind keine besonderen Vorbereitungen notwendig.


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