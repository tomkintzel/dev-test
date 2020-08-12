# Vorbereitungen Windows

#### Inhaltsverzeichnis

- [GIT](#git)
- [Filezilla](#filezilla)
- [Einrichtung SSH](#ssh)
- [WSL 2 aktivieren](#wsl)
- [Linux - Distributionen](#distro)
- [Docker](#docker)
- [VS Code](#vscode) 
  


<a name="git"/>

## GIT

Gehe auf <https://git-scm.com/> und lade dir die Version die zu deinem Betriebssystem passt herunter. Nach dem Download kann die Installation begonnen werden. Im Vorgang können die 
Default Einstellungen übernommen werden.
Als nächstes einmal die GIT BASH starten. Dort werden 3 Einstellungen vorgenommen.

    git config --global user.name "DEIN BENUTZERNAME"
    git config --global user.email "DEINE EMAILADRESSE"
    git config --global core.autocrlf input 

Damit ist erstmal die Konfiguration von GIT abgeschlossen.

<a name="filezilla" />

## Filezilla

Dieses Programm wird dafür benötigt, die Datenbanken die wir Lokal benötigen vom Server herunterzuladen. Dazu auf <https://filezilla-project.org/> gehen. Und den Filezilla Client herunterladen.
Auch hier kann während des Installationsvorgang alle Default Werte übernommen werden. Ist das Programm gestartet kann nun über ein CTRL + S eine neue Serververbindung angelegt werden.  

Zunächst einen neuen Server erstellen. Danach folgende Einstellungen vornehmen.  

    Server: 185.88.213.222
    Benutzer: web-user
    Passwort: <Das entnehmt ihr bitte dem OneNote unter maxcluster>

Klickt auf Verbinden und speichert eure Eingaben ab und lasst die Verbindung zu.  
  
<a name="ssh" />

## Einrichtung SSH  
Als nächstes wird der ssh-key GitLab hinzugefügt, so dass beim arbeiten mit Git nich ständig Benutzername und Passwort eingeben werden muss. Dazu öffne deine Einstellungen auf GitLab.

Unter dem Reiter "SSH-Schlüssel" kann zunächst ein SSH-Key generiert werden. 
öffne dazu eine Git-Bash auf deinem Rechner und führe den Befehl  

    ssh-keygen -t rsa -C "Deine Mindsquare-EMail-Adresse" -b 4096

aus. Nun gehe in deinen Benutzerordner. Am schnellsten geht es mit
    
    %userprofile%

in deiner Adresszeile. Dort wurde nun ein Ordner ".ssh" erstellt. In diesem Ordner liegen nun dein Public- und Privat Key. öffne nun die Datei
id_rsa.pub

mit deinem Editor und kopieren den Key, um ihn in Gitlab einzufügen.

Damit ist die Einrichtung deines SSH-Zugangs auf GITLAB fertig.  

Dieser SSH Key wird auch dem Maxcluster hinzugefügt. Dazu melde dich mit den Anmeldedaten von maxcluster aus dem OneNote auf <https://maxcluster.de> an.  
Wählt den Cluster aus und sucht im Dashboard nach SSH Server. Dort einen neuen SSH Key anlegen. Als Kommentar eure E-Mail angeben und dann wie auf GITLAB den Key einfügen. Speichern und fertig seit ihr.  


Auf einem Windowssystem bietet es sich an auf einem WSL(Windows Subsystem for Linux) zu arbeiten. Daher wird bei der Vorbereitung, auf dieses Setup eingegangen.

<a name="wsl" />

## WSL2 aktivieren

Damit WSL2 genutzt werden kann, müssen einige Vorbereitungen getroffen werden. Zunächst muss überprüft werden welcher Windows-10 Build auf dem Sytem installiert ist.
Dies kann in der cmd durch den Befehl *ver* überprüft werden.

`WSL 2 ist nur in Windows 10-Builds 18917 oder höher verfügbar.`

Ist ein niedriger Build installiert, so muss sich für das Windows Insider Programm angemeldet werden.
Hierzu auf <https://insider.windows.com/de-de/> gehen und die Schritte ausführen.

Der Insider muss nicht mehr unbedingt eingerichtet werden. Seitdem MAY 2020 Update steht auch WSL 2 so zur Verfügung. 

Als nächstes müssen optionale Komponente aktiviert werden. Dazu folgende Befehle in einer Powershell (als Administrator) ausführen.

    dism.exe /online /enable-feature /featurename:Microsoft-Windows-Subsystem-Linux /all /norestart  
    dism.exe /online /enable-feature /featurename:VirtualMachinePlatform /all /norestart

Damit die Einstellungen wirksam werden, muss das System einmal neugestartet werden.  

<a name="distro" />

## Linux-Distribution  

Der nächste Schritt behandelt das Installieren einer Linux Distribution innerhalb des Windowssystem. Es muss also kein Dual Boot eingerichtet werden.

Dazu stellt Windows mittlerweile Linux Distributionen in dem Windows Store bereit. Hier empfehlen wir Ubuntu 18.04 zu installieren.
Ubuntu verfügt über eine sehr große Community und vielen Hilfen bei Problemen.  

Also einfach einfach den Windows Store besuchen und eine Distribution auswählen und installieren. Sollte es noch Probleme geben, bitte <https://docs.microsoft.com/de-de/windows/wsl/install-win10#install-your-linux-distribution-of-choice> besuchen.

Zum jetztigen Zeitpunkt werden die Distributionen in WSL1 gestartet. Um zu überprüfen welches WSL die Distribution verwendet, bitte in der Powershell  
  
    wsl --list --verbose
    # oder  
    wsl -l -v  

eingeben. Ist die Version eurer Distro 2 müsst ihr nichts weiter tun.  
Ansonsten muss die Distro geändert werden. Dazu  

    wsl --set--version <distro-name> 2
    # Beispiel Ubuntu
    wsl --set--version Ubuntu-18.04 2 

ausführen. Dies kann einige Minuten dauern.  

<a name="docker" />

## Docker

Docker Desktop von https://hub.docker.com/editions/community/docker-ce-desktop-windows herunterladen und installieren.  

Docker starten und in den Einstellungen unter >General>`Enable the experimental WSL 2 based engine` aktiveren.
Danach unter Resources>WSL Integration `Enable integration with my default WSL Distro` aktiveren und die unterhalb die Distribution auswählen, die genutzt wird.  
Damit ist Docker auf der Windows "Seite" eingerichtet.


Sind diese Schritte ausgeführt, ist die Vorbereitung des Windows System abgeschlossen.  

<a name="vscode" />

## VSCODE

Damit sich VSCode mit dem WSL verbinden kann muss eine Erweiterung installiert werden.  
Entweder im Erweiterungs Tab nach WSL Remote suchen und installieren oder unter
<https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.vscode-remote-extensionpack> installieren. 

Desweiteren werden folgenede Erweiterungen benötigt:  

- Wordpress Snippets //wpdevtools.io
- PHP Debug //Felix Becker
- Docker
- PHP Intelephense // Ben Mewburn
- GIT Lens
- Git History
- Auto Rename Tag // Jun Han
- Bracket Pair Colorizer // CoenraadS
- Spaces Inside Braces
- EsLint
- phpcs

Ich @tom.kin habe mein Terminal im VSCode nach dieser Anleitung angepasst. Somit muss man keine Distro mehr öffnen muss.  
Dies ist natürlich jedem selbst überlassen. Aber die ersten beiden Schritte würde ich schon empfehlen.  
A better Terminal  
<https://nickymeuleman.netlify.com/blog/linux-on-windows-wsl2-zsh-docker#a-better-terminal>

Sollte ein anderer Editor/IDE benutzt werden. Bitte danach googlen.

Hier geht es weiter mit der Installationen der Entwicklungsumgebung auf der Linux Distro
[Installation](./docs/INSTALLWINDOWS.md)
