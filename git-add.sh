#!/bin/bash

# chmod +x git-add.sh
# ./git-add.sh

YEAR=$(date +%m/%Y)

git add .
git commit -m "Improvements $YEAR"
git push -u origin master