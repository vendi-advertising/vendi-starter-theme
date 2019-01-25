#!/bin/bash

clear

echo 'Enter a value for THEME_NAME_FOR_CONST:'
read THEME_NAME_FOR_CONST

if [[ ! $THEME_NAME_FOR_CONST ]]
then
    echo 'Variable THEME_NAME_FOR_CONST not set... exiting'
    exit 1
fi

echo 'Enter a value for CLIENT_NAMESPACE:'
read CLIENT_NAMESPACE

if [[ ! $CLIENT_NAMESPACE ]]
then
    echo 'Variable CLIENT_NAMESPACE not set... exiting'
    exit 1
fi


find . -type f -name "*.php" -exec sed -i "s/%VENDI_CUSTOM_THEME%/$THEME_NAME_FOR_CONST/g" {} +
find . -type f -name "*.php" -exec sed -i "s/%CLIENT%/$CLIENT_NAMESPACE/g" {} +
composer install

unset THEME_NAME_FOR_CONST
unset CLIENT_NAMESPACE
