#!/bin/bash
cd "${0%/*}"

pwd

# $TODAY=`date '+%Y_%m_%d__%H_%M_%S'`;
git add ./
git commit -m "Commit made today"
git push github master
sleep 15s
