#!/bin/bash
cd "${0%/*}"
TODAY=`date '+%Y_%m_%d__%H_%M_%S'`;
git revert HEAD~1
git add ./
git commit -m "Revert changes from $TODAY"
git push github master
