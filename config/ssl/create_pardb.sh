#!/bin/bash
set -e

# Generate tmp-folder
mkdir tmp
echo '01' > tmp/serial
touch tmp/index tmp/index.attr

# Load pardb settings
export OPENSSL_CONF=~/dev/config/ssl/config/pardb.cnf

# Generate the private-key
openssl genrsa -out private/pardb.key 2048

# Generate the certificate
openssl req -key private/pardb.key -new -out tmp/req.pem

# Load cacert settings
export OPENSSL_CONF=~/dev/config/ssl/config/caconfig.cnf

# sign and generate the certificate
openssl ca -batch -in tmp/req.pem -out certs/pardb.crt

# Generate the PKCS-File
cat private/pardb.key certs/pardb.crt > tmp/pem
openssl pkcs12 -export -passout pass: -out certs/pardb.pfx -in tmp/pem -name "InvoiceSmash Dev Self-Signed SSL Certificate"

# Delete temporary request-file
rm -R tmp

# Send information to the user
printf "\n>>> Damit der Apache-Server die neuen Zertifikate verwenden kann muss der Server mit dem Befehl (docker-compose up -d --build) neu gestartet werden.\n"
