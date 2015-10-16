
cont="$(docker ps -a -q)"
img="$(docker images -q)"

if [[ $cont ]]
    then
    docker stop $cont
    docker rm -v $cont
fi

if [[ $img ]]
    then
    docker rmi $img
fi

docker ps
docker images

