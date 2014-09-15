##!/bin/sh

url_base="http://vimeo.com/toulouseacoustics/videos/page:"
file_out="res_html"
nb_page=6
nb=1

touch $file_out
chmod 777 $file_out
for i in `seq 1 $nb_page`
do
	currenturl=$url_base$i
	echo $currenturl
	wget -q $currenturl
done

cat page* >> $file_out
rm page*

php t_q.php