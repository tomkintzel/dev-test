# Argumente
ARG DB_VERSION=10.0.38

# @see https://hub.docker.com/_/mariadb
FROM mariadb:$DB_VERSION
MAINTAINER mindsquare <info@mindsquare.de>

# Port für eine MySQL-Verbindung
EXPOSE 3306

# Starte den MariaDB-Server
CMD mysqld --user=root
