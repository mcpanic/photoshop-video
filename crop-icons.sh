#!/bin/sh

#for (( j = 0; j < 8; j++ ))
#do  
j=7
  for (( i = 0; i < 9; i++ ))
  do
    x=`expr $i \* 66 + 4`
    y=`expr 7 \* 64 + 103`
    echo "convert -crop 51x41+$x+$y tools.jpeg $i-$j.png"
    convert -crop 51x41+$x+$y tools.jpeg icons/$i-7.png
    echo "###"
  done
    echo "######"
#done
