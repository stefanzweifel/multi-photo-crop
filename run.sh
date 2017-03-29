#!/bin/bash

FUZZ=20
PRUNE=2
SUFFIX="__processed.png";

find ./input -type f -name '*.png' -print0 | while IFS= read -r -d '' file; do
    FILENAME=$(basename "$file")

    echo "Processing $FILENAME file...";

    ./bin/multicrop -c 10,10 -d 25 -f $FUZZ -p $PRUNE "$file" dist/$FILENAME$SUFFIX >> /dev/null 2>&1

    # Run with -c SouthWest when the lower left corner is more white than the top left corner
    # -c SouthWest

done
