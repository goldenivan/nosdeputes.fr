#!/bin/bash

if [[ $1 != "all" && $1 != "recent" ]]; then
  echo "usage: compute_latest.sh all/recent"
  exit 1
fi

. ../../bin/db.inc

echo "SELECT numero FROM question_ecrite order by numero DESC limit 1" | mysql $MYSQLID $DBNAME | grep -v numero > dernier_numero.txt

if [[ $1 -eq "all" ]]; then
  sql_string='SELECT source FROM question_ecrite WHERE reponse = ""'
else
  sql_string='SELECT source FROM question_ecrite WHERE reponse = "" AND date > DATE_SUB(CURDATE(), INTERVAL 75 DAY)'
fi
echo $sql_string | mysql $MYSQLID $DBNAME | grep -v source > liste_sans_reponse.txt

rm -f html/*

#log cette partie très verbeuse
perl download_questions.pl > /tmp/download_questions.log

for file in `grep -L "The page cannot be found" html/*`; do
	fileout=$(echo $file | sed 's/html/json/' | sed 's/\.htm/\.xml/')
#	perl cut_quest.pl $file > $fileout
	python parse.py $file > $fileout
done;

