# Enwicklungsumgebung Mindsquare

Einfachere Entwicklung mit Docker

Beinhaltet:

- [Vorbereitungen](#vorbereitungen)
- [Installation](./docs/install.md)
- [Benutzung](#benutzung)

## Vorbereitungen  

### Windows

Auf einem Windowssystem bietet es sich an auf einem WSL(Windows Subsystem for Linux) zu arbeiten. Daher wird bei der Vorbereitung, auf dieses Setup eingegangen.

#### WSL2 aktivieren

Damit WSL2 genutzt werden kann, müssen einige Vorbereitungen getroffen werden. Zunächst muss überprüft werden welcher Windows-10 Build auf dem Sytem installiert ist.
Dies kann in der cmd durch den Befehl *ver* überprüft werden.

`WSL 2 ist nur in Windows 10-Builds 18917 oder höher verfügbar.`

Ist ein niedriger Build installiert, so muss sich für das Windows Insider Programm angemeldet werden.
Hierzu auf <https://insider.windows.com/de-de/> gehen und die Schritte ausführen.

Als nächstes müssen optionale Komponente aktiviert werden. Dazu folgende Befehle in einer Powershell (als Administrator) ausführen.

    dism.exe /online /enable-feature /featurename:Microsoft-Windows-Subsystem-Linux /all /norestart  
    dism.exe /online /enable-feature /featurename:VirtualMachinePlatform /all /norestart

Damit die Einstellungen wirksam werden, muss das System einmal neugestartet werden.  

#### Linux-Distribution  

Der nächste Schritt behandelt das Installieren einer Linux Distribution innerhalb des Windowssystem. Es muss also kein Dual Boot eingerichtet werden.

Dazu stellt Windows mittlerweile Linux Distributionen in dem Windows Store bereit. Hier empfehlen wir Ubuntu zu installieren.
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
    wsl --set--version Ubuntu 2 

ausführen. Dies kann einige Minuten dauern.  

Sind diese Schritte ausgeführt, ist die Vorbereitung des Windows System abgeschlossen.  

### MacOS  

Hier sind keine besonderen Vorbereitungen notwendig.

### VSCODE

Damit sich VSCode mit dem WSL verbinden kann muss eine Erweiterung installiert werden.  
Entweder im Erweiterungs Tab nach WSL Remote suchen und installieren oder unter
<https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.vscode-remote-extensionpack> installieren.  

Ich @tom.kin habe mein Terminal im VSCode nach dieser Anleitung angepasst. Somit muss man keine Distro mehr öffnen muss.  
Dies ist natürlich jedem selbst überlassen. Aber die ersten beiden Schritte würde ich schon empfehlen.  
A better Terminal  
<https://nickymeuleman.netlify.com/blog/linux-on-windows-wsl2-zsh-docker#a-better-terminal>

Sollte ein anderer Editor/IDE benutzt werden. Bitte danach googlen.
