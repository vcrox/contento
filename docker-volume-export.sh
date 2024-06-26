#!/usr/bin/env bash

: ${1:?First argument is required}
: ${2:?Second argument is required}

VOL_SRC=${1}
VOL_DST=${2}
# echo "${VOL_SRC}"

VOL_SRC_CHECK=$(docker volume ls -q -f name="^${VOL_SRC}$")
# echo "${VOL_SRC_CHECK}"
VOL_DST_CHECK=$(ls ${VOL_DST} 2>/dev/null)

# if [ "${VOL_SRC}" != "${VOL_SRC_CHECK}" ]; then
  # echo >&2 'Volume "'${VOL_SRC}'" does not exist'
  # exit 1
# fi

echo -n 'Volume "'${VOL_SRC}'" will be exported as "'${VOL_DST}'" [y/N] '

read ANSWER

if [ "${ANSWER}" != "y" ]; then
  echo 'Canceled'
  exit 0
fi

if [ "${VOL_DST}" == "${VOL_DST_CHECK}" ]; then
  echo -n 'Destination file "'${VOL_DST}'" already exists. It will be overwritten. [y/N] '
  read ANSWER
  if [ "${ANSWER}" != "y" ]; then
    echo 'Canceled'
    exit 0
  fi
  unlink ${VOL_DST}
fi

echo "Export in progress"
docker run --rm \
  -v ${VOL_SRC}:/volume \
  --workdir=/volume \
  busybox sh -c 'tar -cOzf - .' >${VOL_DST}
