rm -rf /dev/shm/cache/*
app/console cache:clear
chmod -R 777 /dev/shm
echo 'flush_all' | nc localhost 11211
