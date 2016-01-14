#!/bin/bash
#this script is for getting teh stats from bioc web site and upload to pathway database every day morning 2:30
	wget "http://bioconductor.org/packages/stats/bioc/pathview.html"
	wget -q --spider "http://bioconductor.org/packages/stats/bioc/pathview.html"
	 out=$?
        if [ $out = 0 ]
        then
		
		months=( "Jan" "Feb" "Mar" "Apr" "May" "Jun" "Jul" "Aug" "Sep" "Oct" "Nov" "Dec" )
		count=0
		for i in "${months[@]}"
		do
		temp=`grep -oh "$i/[0-9]*" pathview.html`
		month=`echo $temp|cut -c1-3`
		year=`echo $temp | grep -oh '[0-9]*'`
		ipadd=$(grep ">$month" pathview.html | grep -o ">[0-9]*<" | grep -o "[0-9]*" | head -1)
		downloads=$(grep ">$month" pathview.html | grep -o ">[0-9]*<" | grep -o "[0-9]*" | tail -1)

		echo $ipadd "-" $downloads "-" $month "-" $year


		echo "delete from biocStatistics where month = '$month' and year = '$year';"|mysql -uroot -pAdminAdmin PathwayWeb
		echo "insert into biocStatistics values('$month','$year',$ipadd,$downloads,'','');" |mysql -uroot -pAdminAdmin PathwayWeb

		done

		echo "completed inserting"

		rm pathview.html
	else
		echo "Page Not found exception occured in getting pathview stats page"
	fi

