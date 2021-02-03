#!/bin/bash
set -e

# Generate tmp-folder
mkdir tmp
echo '01' > tmp/serial
touch tmp/index tmp/index.attr

# Load default settings
export OPENSSL_CONF=~/dev/config/ssl/config/default.cnf

# Generate the private-key
openssl genrsa -out private/default.key 2048

# Generate the certificate
openssl req -key private/default.key -new -out tmp/req.pem

# Load cacert settings
export OPENSSL_CONF=~/dev/config/ssl/config/caconfig.cnf

# sign and generate the certificate
openssl ca -batch -in tmp/req.pem -out certs/default.crt

# Generate the PKCS-File
cat private/default.key certs/default.crt > tmp/pem
openssl pkcs12 -export -passout pass: -out certs/default.pfx -in tmp/pem -name "InvoiceSmash Dev Self-Signed SSL Certificate"

# Delete temporary request-file
rm -R tmp

# Send information to the user
printf "\n>>> Damit der Apache-Server die neuen Zertifikate verwenden kann muss der Server mit dem Befehl (docker-compose up -d --build) neu gestartet werden.\n"
