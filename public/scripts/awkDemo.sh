
cd ../all
file=`du -sm * | awk '$1 > 98' |awk '{print $2}' `

echo $file
if [ ${#file} != 0 ]
        then
        analysisFiles=`ls -lrt $file | awk '{print $9}'`

        print "$analysisFiles" | while IFS= read -r analFile
        do
        if [ ${#analFile} != 0 ]
        then
        currsize=`du -sm $file | grep -o "^\S\+" `
        echo $currsize
        if [ $currsize -lt 51 ]
        then
        echo $file "size reached to 50 "
        break
        fi
        echo "delelting" $analFile
        if [ $? -eq 0 ]
        then
        echo "update analyses set id=0 where analysis_id=\""$analFile"\"" |mysql -u root -ptcs@YASH pathway
        fi
        fi
        done
        fi

