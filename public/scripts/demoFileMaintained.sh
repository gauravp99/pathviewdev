#!/bin/bash

echo "starting removing the demo files"
#change director to argument specified
argument="$1"
if [[ $argument == *"/all/demo"* ]]
then
	cd $argument

	find . -mmin +1440 -delete
else
	echo "path specified is not the correct path"
fi
#1440 indicates 24 hours time
echo "removing the demo files completed"
