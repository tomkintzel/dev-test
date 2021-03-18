#### Neue Entwicklungsumgebung
### Erst verwenden, wenn das Live System auf PHP 8 geudated wurde

### Inhaltsverzeichnis
- [GIT](#git)
- [SSH](#ssh)
- [NODEJS](#node)
- [DOCKER](#docker)
- [Docker-compose](#docker-compose)
- [Composer](#composer)
- [WordPress Repo](#repo)
- [Datenbank](#datenbank)
- [Git Flow](#flow)

# Es wird angenommen, dass ihr root Rechte habt. Daher die Befehle ohne sudo.


<a name="git"/>

## Git einrichten
In der Regel ist mittlerweile Git standardmäßig installiert. Daher muss nur noch eure Config angepasst werden.

Dazu folgende Befehle mit euren Daten eingeben.
	
	git config --global user.name "Daniel Ricciardo"
    git config --global user.email "john@doe.com"  
    git config --global core.autocrlf input 


<a name="ssh"/>

## SSH einrichten

In diesem Zuge kann auch direkt der .ssh Ordner im root Verzeichnis erstellt werden.  

    mkdir ~/.ssh
    
Nun wird der Public Key aus dem Windows(falls vorhanden) in das Linux System kopiert. Geht zunächst in euren Benutzer Ordner und kopiert die beiden id_rsa Dateien und fügt sie
in den neu erstellten Ordner auf dem Linux System ein. Am schnellsten gelangt ihr in euer root Verzeichnis über die Adresszeile 
eures Windows Explorer. Dazu tippt ihr ein  

    \\wsl$
    
Nun wählt ihr eure Distro aus. Jetzt können die Dateien in den .ssh Ordner eingefügt werden.

Um eine spätere Fehlermeldung zu vermeiden.

	chmod 400 ~/.ssh/id_rsa

ausführen.


<a name="node"/>

## Node per NVM installieren
Die einfachste Möglichkeit node zu installieren und zwischen verschiedenen Versionen zu wechseln, ist die Installation eines Node Version Managers.

Dazu auf <https://github.com/nvm-sh/nvm> gehen und den curl Befehl kopieren und in der Konsole ausführen.

Dieser Befehl sieht so aus:

	curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.37.2/install.sh | bash

Mit diesem Befehl wird der NVM heruntergeladen und installiert. Da hinter dem curl Befehl ein bash steht. Wird dieser auch mit bash ausgeführt und das Script wird dauerhaft in der .bashrc gespeichert.
Solltet ihr zum Beispiel zsh als Konsole benutzen, tauscht das bash gegen ein zsh aus.
Nach der Installation solltet ihr die Konsole neustarten.
Um die erfolgreiche Installation von nvm zu überprüfen,

	nvm --version

eingeben. Jetzt sollte die Versionsnummer in der Konsole angezeigt werden.
