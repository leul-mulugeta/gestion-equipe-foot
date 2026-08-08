#!/bin/sh
# Génère la paire de clés RS256 partagée entre le service d'authentification et le serveur de données
#
# Les deux clés sont écrites dans des volumes distincts : le service d'authentification ne monte
# que la clé privée (signature), le serveur de données que la clé publique (vérification)

set -e

apk add --no-cache openssl > /dev/null

# La clé privée n'est générée qu'une fois : la régénérer invaliderait tous les tokens en circulation
if [ ! -f /private/private.pem ]; then
    openssl genpkey -algorithm RSA -pkeyopt rsa_keygen_bits:2048 -out /private/private.pem

    # 33 = www-data dans les images Debian, l'utilisateur qui exécute PHP
    chown 33:33 /private/private.pem
    chmod 600 /private/private.pem
fi

# La clé publique est redérivée à chaque démarrage pour garantir qu'elle correspond toujours à la clé privée courante
openssl rsa -in /private/private.pem -pubout -out /public/public.pem
chmod 644 /public/public.pem
