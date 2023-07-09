#!/bin/sh

install=false

# shellcheck disable=SC2039
while [[ "$#" -gt 0 ]]; do
    case $1 in
        -i|--install) install=true; shift ;;
#        -u|--uglify) uglify=1 ;;
        *) echo "Unknown parameter passed: $1"; exit 1 ;;
    esac
    shift
done
# shellcheck disable=SC2039
if [[ "$install" == true ]]; then
      echo 'Installation';
      docker-compose --env-file .env.install up --build;
    else
      echo 'Run';
      docker-compose --env-file .env.local up;
fi

