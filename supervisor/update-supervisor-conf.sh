#!/usr/bin/env bash
cp olx.conf /etc/supervisor/conf.d/olx.conf
supervisorctl reread
supervisorctl reload
supervisorctl status