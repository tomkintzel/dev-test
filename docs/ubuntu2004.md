#### Neue Entwicklungsumgebung
### Erst verwenden, wenn das Live System auf PHP 8 geudated wurde

### Inhaltsverzeichnis
- [NODEJS](#node)
- [DOCKER](#docker)
- [Docker-compose](#docker-compose)
- [Composer](#composer)
- [WordPress Repo](#repo)
- [Datenbank](#datenbank)
- [Git Flow](#flow)

# Es wird angenommen, dass ihr root Rechte habt. Daher die Befehle ohne sudo.

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
