#!/bin/bash
set -e

# Load cacert settings
export OPENSSL_CONF=~/dev/config/ssl/config/caconfig.cnf

# Generate the private-key
openssl genrsa -out private/cacert.key 2048

# Generate the certificate
openssl req -x509 -key private/cacert.key -days 1825 -out certs/cacert.crt -outform PEM

# Regenerate normal certificates
./create_mindsquare-network.sh

# Send information to the user
printf ">>> Bitte installiere die Datei (~/dev/config/ssl/certs/cacert.crt) auf dem Windows-Rechner als Vertrauenswürdige Stammzertifizierungsstelle.\n"
