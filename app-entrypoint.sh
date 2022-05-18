#!/bin/bash

if [[ -n "$CI" ]]; then
    echo "this block will only execute in a CI environment"
    echo "now running script commands"
    # this is how GitLab expects your entrypoint to end, if provided
    # will execute scripts from stdin
    exec /bin/bash

else
    echo "this block will only execute in NON-CI environments"
    # execute the command as if passed to the container normally

    set -e

    cron

    exec "$@"

    exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.app.conf
fi

