#!/bin/bash

ROOTURL="http://www2.assemblee-nationale.fr"

mkdir -p presents.tocheck

function compute_org {
  ORGID=$1
  ORGNAME=$2
  HORAIRE=$3
  curl -sL "$ROOTURL/layout/set/ajax/content/view/embed/$ORGID" |
    tr "\n" " "                  |
    sed -r 's/<h3>(Décisions de (Questure de )?la )?R[ée]union d/\n/gi' |
    grep 'href="/'               |
    grep -v '/convocation/'      |
    grep -v '/(offset)/'         |
    sed 's/^.*href="//'          |
    sed 's/".*$//'               |
    while read url; do
      outf=$(echo $ROOTURL$url | sed 's|/|_|g')
      if ! test -s "html/$outf"; then
        perl download_one.pl $ROOTURL$url
	    perl parse_presents.pl html/$outf "$ROOTURL$url" "$ORGNAME" "$HORAIRE" > presents.tocheck/$outf
      fi
    done
}
  
compute_org 42864 "Bureau de l'Assemblée nationale" "10:00"
compute_org 47173 "Conférence des présidents" "10:00"
compute_org "47173/(offset)/6" "Conférence des présidents" "10:00"
compute_org "47173/(offset)/12" "Conférence des présidents" "10:00"
compute_org "47173/(offset)/18" "Conférence des présidents" "10:00"
compute_org "47173/(offset)/24" "Conférence des présidents" "10:00"
compute_org "47173/(offset)/30" "Conférence des présidents" "10:00"
compute_org "47173/(offset)/36" "Conférence des présidents" "10:00"
compute_org "47173/(offset)/42" "Conférence des présidents" "10:00"
compute_org "47173/(offset)/48" "Conférence des présidents" "10:00"
compute_org "47173/(offset)/54" "Conférence des présidents" "10:00"
compute_org "47173/(offset)/60" "Conférence des présidents" "10:00"
compute_org 48302 "Questure" "08:30"

