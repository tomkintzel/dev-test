# Diese Datei muss in der ~/.bashrc mit "source {{Pfad zur dieser Datei}}"
# hinzugefügt werden. Mit dieser Datei werden von der Entwicklungsumgebung
# weitere Befehle hinzugefügt, die bei der Entwicklung helfen könnten.

# Füge alle Bin-Ordner zu der PATH-Variable hinzu
PATH=$PATH:\
"$(dirname $BASH_SOURCE[0])/bin":\
"$(dirname $BASH_SOURCE[0])/www/mindsquare-network/htdocs/node_modules/.bin":\
"$(dirname $BASH_SOURCE[0])/www/mindsquare-network/htdocs/vendor/bin"

# Füge Alias-Befehle die gestarteten Docker-Container
if ! command -v  mysql &> /dev/null; then
    alias mysql='f(){ if [ -t 0 ]; then docker exec -it $(docker-compose ps -q db) mysql "$@"; else docker exec -i $(docker-compose ps -q db) mysql "$@"; fi; unset -f f;};f'
fi
if ! command -v  mysqldump &> /dev/null; then
    alias mysqldump='docker exec -it $(docker-compose ps -q db) mysqldump "$@"'
fi

# Füge weitere Alias-Befehle die mit Hilfe von Docker ausgeführt werden können
if ! command -v composer &> /dev/null; then
    alias composer='docker run --rm -it -v $PWD:/app composer "$@"'
fi

# Öffne ein SSH-Tunnel zu Memcache-Server auf dem MaxCluster
alias memcache='ssh -N -f maxcluster -L 0.0.0.0:11211:127.0.0.1:11211'
if [ -z "$(ss -lntu | grep ':11211')" ]; then
    memcache
fi
