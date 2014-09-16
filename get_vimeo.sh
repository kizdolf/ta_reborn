##!/bin/sh

url_base="http://vimeo.com/toulouseacoustics/videos/page:"
file_out="res_html"
file_log="log_vimeo.log"
nb_page=6
nb=1
y="y"

touch $file_out
chmod 777 $file_out
date
date >> $file_log
echo "Début du script. "
echo "Début du script. " >> $file_log
echo "Url de base : "$url_base
echo "nombre de page : "$nb_page
echo "Press Enter if it's look good."
read "a"
echo "Let's do that."
echo "Début du  téléchargment des pages"
echo "Début du  téléchargment des pages" >> $file_log
for i in `seq 1 $nb_page`
do
	currenturl=$url_base$i
	echo "Capturing page : "$currenturl
	echo "Capturing page : "$currenturl >> $file_log
	wget -q $currenturl
done

cat page* >> $file_out
echo "Concaténation éffectuée."
if [[ $1 != "full" ]]; then
	read -r -p "Voulez vous conserver la base de donnée existante? (be carful) [y/N] " response
	echo "Lançement du script php.\n\n"
	date >> $file_log
	echo "Lançement du script php.\n\n" >> $file_log
	case $response in
	    [yY][eE][sS]|[yY]) 
		php t_q.php $file_log $1 
	        ;;
	    *)
		php t_q.php $file_log $1 del
	        ;;
	esac
else
	php t_q.php $file_log 0 del
fi
echo "Fin du script php."
date >> $file_log
echo "Fin du script php." >> $file_log
if [[ $1 != "full" ]]; then
	read -r -p "Voulez vous supprimer les fichiers téléchargés? [y/N] " response
	case $response in
	    [yY][eE][sS]|[yY]) 
	  	rm $file_out
		rm page*
		echo "fichiers supprimés."
		echo "suppression des fichiers " >> $file_log
	        ;;
	    *)
	      	echo "fichiers conservés."
		echo "conservation des fichiers " >> $file_log
	        ;;
	esac
else
	rm $file_out
	rm page*
	echo "fichiers supprimés."
	echo "suppression des fichiers " >> $file_log
fi


echo "Fin du script."
date
date >> $file_log
echo "FIn du script" >> $file_log
