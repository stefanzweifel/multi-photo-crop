#!/bin/bash


FILES=src/*.png
FUZZ=20
PRUNE=2
SUFFIX="__processed.png";


for f in $FILES; do

    FILENAME=$(basename $f);
    NEW_NAME=$FILENAME$SUFFIX;

    echo "Processing $FILENAME file...";

    ./bin/multicrop -c SouthWest -f $FUZZ -p $PRUNE $f dist/$FILENAME$SUFFIX
done
