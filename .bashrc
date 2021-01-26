# Diese Datei muss in der ~/.bashrc mit "source {{Pfad zur dieser Datei}}"
# hinzugefügt werden. Mit dieser Datei werden von der Entwicklungsumgebung
# weitere Befehle hinzugefügt, die bei der Entwicklung helfen könnten.

DEV_DIR="$(dirname $BASH_SOURCE[0])"

# Füge alle Bin-Ordner zu der PATH-Variable hinzu
PATH=$PATH:\
"$DEV_DIR/bin":\
"$DEV_DIR/www/mindsquare-network/htdocs/node_modules/.bin":\
"$DEV_DIR/www/mindsquare-network/htdocs/vendor/bin"

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

# Führt ein PHP-Befehl mit festen mappings aus
function php56 {
	if [[ "$(pwd)" == "$DEV_DIR/www"* ]]; then
		PWD=$(pwd)
		DIFF=${PWD//"$DEV_DIR/www"/}
		FILE="/var/www/html$DIFF/$1"
		docker exec $(docker-compose ps -q php_56) php "$FILE"
	fi
}
function php72 {
	if [[ "$(pwd)" == "$DEV_DIR/www"* ]]; then
		PWD=$(pwd)
		DIFF=${PWD//"$DEV_DIR/www"/}
		FILE="/var/www/html$DIFF/$1"
		docker exec $(docker-compose ps -q php_72) php "$FILE"
	fi
}
