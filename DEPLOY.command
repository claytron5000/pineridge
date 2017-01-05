#!/bin/bash
cd "${0%/*}"


TODAY=`date '+%Y_%m_%d__%H_%M_%S'`;
git add ./
git commit -m "Commit made $TODAY"
git push github master
sleep 15s
