# !/bin/bash
echo "Este script eliminara todos los archivos volt*.php... usar con precaucion"
read -p "Estas seguro? " -n 1 -r
echo    # (optional) move to a new line
if [[ $REPLY =~ ^[Ss]$ ]]
then
	echo "Estos son los archivos que se eliminan!!!"
	find . -regex ".*\.volt.*\.php"
	echo "ELIMINANDO!!!"
	find . -regex ".*\.volt.*\.php" -delete
	echo "Listo"
else
	echo "No se elimino nada!"
fi
