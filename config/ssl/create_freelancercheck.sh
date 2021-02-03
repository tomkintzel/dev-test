#!/bin/bash
set -e

# Generate tmp-folder
mkdir tmp
echo '01' > tmp/serial
touch tmp/index tmp/index.attr

# Load freelancercheck settings
export OPENSSL_CONF=~/dev/config/ssl/config/freelancercheck.cnf

# Generate the private-key
openssl genrsa -out private/freelancercheck.key 2048

# Generate the certificate
openssl req -key private/freelancercheck.key -new -out tmp/req.pem

# Load cacert settings
export OPENSSL_CONF=~/dev/config/ssl/config/caconfig.cnf

# sign and generate the certificate
openssl ca -batch -in tmp/req.pem -out certs/freelancercheck.crt

# Generate the PKCS-File
cat private/freelancercheck.key certs/freelancercheck.crt > tmp/pem
openssl pkcs12 -export -passout pass: -out certs/freelancercheck.pfx -in tmp/pem -name "InvoiceSmash Dev Self-Signed SSL Certificate"

# Delete temporary request-file
rm -R tmp

# Send information to the user
printf "\n>>> Damit der Apache-Server die neuen Zertifikate verwenden kann muss der Server mit dem Befehl (docker-compose up -d --build) neu gestartet werden.\n"
