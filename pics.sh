#!/bin/bash
cd img
rm ../thumbs/*
convert -size 200x150 xc:none -fill white -draw 'rectangle 0,0,200,150' ../thumb/back.png;
for i in *;
do
convert $i -thumbnail 200x150 ../thumb/$i.1;
composite -compose atop -gravity center ../thumb/$i.1 ../thumb/back.png ../thumb/$i;
rm ../thumb/$i.1;
done
rm ../thumb/back.png;
ftp two.xthost.info;

