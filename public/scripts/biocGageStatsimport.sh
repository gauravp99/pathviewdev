#!/bin/bash
	wget "http://bioconductor.org/packages/stats/bioc/gage.html"
	wget -q --spider "http://bioconductor.org/packages/stats/bioc/gage.html"
	out=$?
	if [ $out = 0 ]	
	then	
		months=("Jan" "Feb" "Mar" "Apr" "May" "Jun" "Jul" "Aug" "Sep" "Oct" "Nov" "Dec" )
		count=0
		for i in "${months[@]}"
		do
			temp=`grep -oh "$i/[0-9]*" gage.html`
			month=`echo $temp|cut -c1-3`
			year=`echo $temp | grep -oh '[0-9]*'`
			ipadd=$(grep ">$month" gage.html | grep -o ">[0-9]*<" | grep -o "[0-9]*" | head -1)
			downloads=$(grep ">$month" gage.html | grep -o ">[0-9]*<" | grep -o "[0-9]*" | tail -1)

			echo $ipadd "-" $downloads "-" $month "-" $year


			echo "delete from biocGagestatistic where month = '$month' and year = '$year';"|mysql -uroot -pAdminAdmin PathwayWeb
			echo "insert into biocGagestatistic values('$month','$year',$ipadd,$downloads);" |mysql -uroot -pAdminAdmin PathwayWeb

		done

		echo "completed inserting"
	else
		echo "not able to hit the gave statistics page"
	fi	
		rm "gage.html"

