#!/bin/bash
cd -- "$(dirname '$BASH_SOURCE')"
today=`date '+%Y_%m_%d__%H_%M_%S'`;
git add ./
git commit -m "Commit made ${today}"
git push github master
