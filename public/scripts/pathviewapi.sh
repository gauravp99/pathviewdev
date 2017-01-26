############################### PATHWAY API #############################################
# This API is built to carry the pathview analysis using the command line.
# The script can be executed in Linux and UNIX like operating system.
# For more information , try running the sript with --help option to know the available
# arguments for the API.
#########################################################################################

#!/bin/bash

argument_list=""
API_PATH='http://pathview.uncc.edu/api/analysis'

function usage()
{
	echo "Usage: `basename $0` options --gene_data | --cpd_data  [--gene_id] [-- cpd_id] [--pathway_id] ... [--other options]";
	echo "More information available in help page"
	echo 
	echo -e "http://pathview.uncc.edu/api_tutorial#input" #Green Color
	echo
	echo "List of valid Arguments with default value "
	echo
	echo "--gene_data       Gene Data accepts data matrices in tab- or comma-delimited format (txt or csv)"
	echo "--cpd_data        Compound Data accepts data matrices in tab- or comma-delimited format (txt or csv)"
	echo "--gene_reference  This argument specifies the column numbers for controls"         
	echo "--gene_sample     This argument specifies the column numbers for cases"
	echo "--gene_compare    This argument specifies whether the experiment samples are paired or not. Default value is paired"
	echo "--cpd_reference   This argument specifies the column numbers for controls"         
	echo "--cpd_sample      This argument specifies the column numbers for cases"
	echo "--cpd_compare     This argument specifies whether the experiment samples are paired or not. Default value is paired"
	echo "--suffix          This argument specifies the suffix to be added after the pathway name as part of the output graph file name."
	echo "--pathview_id     This argument specifies the KEGG pathway IDs, usually 5 digit long.Pathway IDs can also be provided in a comma separated file"
	echo "--gene_id         ID type used for the Gene Data"
	echo "--cpd_id          ID type used for the Compound Data"
	echo "--auto_sel        This argument is recommended if the user is not sure what pathway(s) to view."	
	echo "--species         Either the KEGG code, scientific name or the common name of the target species"
	echo "--kegg            This argument specifies whether to render the pathway as native KEGG graph (.png) or using Graphviz layout engine (.pdf)"
	echo "--layer           This argument controls plotting layers.Default value is F(False)"
	echo "--split           This argument specifies whether split node groups are split to individual nodes.Default value is F(False)"
	echo "--expand          This argument specifies whether the multiple-gene nodes are expanded into single-gene nodes. Default value is F(False)"
	echo "--multistate      This argument specifies whether multiple states (samples or columns) Gene Data or Compound Data should be integrated and plotted in the same graph. Default is T(True)"
	echo "--matched         This argument specifies whether the samples of Gene Data and Compound Data are paired. Default match data is T(True)"
	echo "--discrete_gene   Describes whether Gene Data should be treated as discrete.Default value is F(False)" 
	echo "--discrete_cpd    Describes whether Compound Data should be treated as discrete.Default value is F(False)"
	echo "--keyposition     Controls the position of color key(s). Default value is topleft"
	echo "--signatureposition  Controls the position of pathview signature. Default value is bottomright"
	echo "--offset          This argument specifies how much compound labels should be put above the default position or node center.Default value is 1.0 " 
	echo "--align           This argument specifies how the color keys are aligned when both Gene Data and Compound Data are not NULL. Potential values are x and y. Default value is x"
	echo "--limit_gene      The limit values for Gene Data when converting them to pseudo colors. This field is a numeric field with default value 1"
	echo "--limit_cpd       The limit values for Compound Data when converting them to pseudo colors. This field is a numeric field with default value 1"
	echo "--bins_gene       This argument specifies the number of levels or bins for Gene Data when converting them to pseudo colors. Default value is 10. " 
	echo "--bins_cpd        This argument specifies the number of levels or bins for Compound Data when converting them to pseudo colors. Default value is 10. " 
	echo "--node_sum        This argument calculates the node summary given that multiple genes or compounds are mapped to it. Default Value is sum."
	echo "--na_color        This argument specify the color used for NA's or missing values in Gene Data and Compound Data. Potential value can be transparent or grey. Default is transparent"
	echo "--low_gene        This argument specify the color spectra to code Gene Data.Default value is green.Hex color codes can also be given (#00FF00, #D3D3D3)"
	echo "--mid_gene        This argument specify the color spectra to code Gene Data.Default value is gray.Hex color codes can also be given (#00FF00, #D3D3D3)"
	echo "--high_gene       This argument specify the color spectra to code Gene Data.Default value is red.Hex color codes can also be given (#00FF00, #D3D3D3)"
	echo "--low_cpd        This argument specify the color spectra to code Gene Data.Default value is green.Hex color codes can also be given (#00FF00, #D3D3D3)"
	echo "--mid_cpd        This argument specify the color spectra to code Gene Data.Default value is gray.Hex color codes can also be given (#00FF00, #D3D3D3)"
	echo "--high_cpd       This argument specify the color spectra to code Gene Data.Default value is red.Hex color codes can also be given (#00FF00, #D3D3D3)"
	echo "--username       The registered email to access the API."
	echo "--password       The password for the registered account to access the API." 
	echo
	echo "Below two options can be used to get the help and sample examples. However this options should be given separately." 
	echo "--help      This will provide the list of all valid arguments along with the description."
	echo "--example  This will give a sample invocation of the API with an eg."
}


function example_1()
{
	echo
	echo "Gene file can be downloaded from the link: http://pathview.uncc.edu/data/gse16873.d3.txt"
	echo "Compound file can be downloaded from the link: http://pathview.uncc.edu/data/sim.cpd.data2.txt "
	echo 
	echo "The gene and compound files must be present in the location where the API is getting executed."
	echo "More examples and detailed description can be found in in the link: http://pathview.uncc.edu/api_examples" 
	echo "`basename $0`  --gene_data gse16873.d3.txt --cpd_data sim.cpd.data2.csv --species hsa --pathway_id 00640"

	echo 
	echo "Make sure the hex color codes (for eg. '#00FF00')are given in single quotes and the argument values with spaces should be given in single quotes.For eg. if cpd_id value is CAS Registry Number then the correct way of specifying it is --cpd_id 'CAS Registry Number'"

}

##Check if no argument is supplied to the script
if [ $# -eq 0 ] 
then
   echo "Please provide the argument with the script."
   echo "Usage: `basename $0` options [--gene_data] [--cpd_data] [--gene_reference] [--gene_sample] ... [--other options]";
   echo "Try pathviewapi --help  for more information."
   exit
fi

while [ "$1" != "" ]; do
    case $1 in
        --gene_data )           shift
                                gene_data=$1
				argument_list+="-F gene_data=@$gene_data "
                                ;;
        --cpd_data )           shift
                                cpd_data=$1
				argument_list+="-F cpd_data=@$cpd_data "
                                ;;
        --species )           shift
                                species=$1
				argument_list+="-F species=`echo $species | cut -d'-' -f 1` "
                                ;;
        --gene_id )           shift
                                gene_id=$1
				argument_list+="-F gene_id=$gene_id "
                                ;;
        --cpd_id )           shift
                                cpd_id=$1
				if ( echo "$cpd_id" | grep -q ' ' )
				then 
				   argument_list+="-F 'cpd_id=$cpd_id' "
				else
				   argument_list+="-F cpd_id=$cpd_id "
				fi
                                ;;
        --auto_sel )          shift
                                auto_sel=$1
				argument_list+="-F auto_sel=$auto_sel "
                                ;;
        --pathway_id )          shift
                                pathway_id=$1
				if ( echo "$pathway_id" | grep -q ' ' )
				then 
				  argument_list+="-F pathway_id='$pathway_id' "
				else
				  argument_list+="-F pathway_id=$pathway_id "
				fi
                                ;;
        --suffix )              shift
                                suffix=$1
				argument_list+="-F suffix=$suffix "
                                ;;
        --kegg )                shift
                                kegg=$1
				argument_list+="-F kegg=$kegg "
                                ;;
        --layer )               shift
                                layer=$1
				argument_list+="-F layer=$layer "
                                ;;
        --split )               shift
                                split=$1
				argument_list+="-F split=$split "
                                ;;
        --expand )              shift
                                expand=$1
				argument_list+="-F expand=$expand "
                                ;;
        --multistate )           shift
                                multistate=$1
				argument_list+="-F multistate=$multistate "
                                ;;
        --matched )           shift
                                matched=$1
				argument_list+="-F matched=$matched "
                                ;;
        --discrete_gene )           shift
                                discrete_gene=$1
				argument_list+="-F discrete_gene=$discrete_gene "
                                ;;
        --discrete_cpd )           shift
                                discrete_cpd=$1
				argument_list+="-F discrete_cpd=$discrete_cpd "
                                ;;
        --keyposition )           shift
                                keyposition=$1
				argument_list+="-F keyposition=$keyposition "
                                ;;
        --signatureposition )           shift
                                signatureposition=$1
				argument_list+="-F signatureposition=$signatureposition "
                                ;;
        --offset )           shift
                                offset=$1
				argument_list+="-F offset=$offset "
                                ;;
        --align )           shift
                                align=$1
				argument_list+="-F align=$align "
                                ;;
        --limit_gene )           shift
                                limit_gene=$1
				argument_list+="-F limit_gene=$limit_gene "
                                ;;
        --limit_cpd )           shift
                                limit_cpd=$1
				argument_list+="-F limit_cpd=$limit_cpd "
                                ;;
        --bins_gene )           shift
                                bins_gene=$1
				argument_list+="-F bins_gene=$bins_gene "
                                ;;
        --bins_cpd )           shift
                                bins_cpd=$1
				argument_list+="-F bins_cpd=$bins_cpd "
                                ;;
        --low_gene )           shift
                                low_gene=$1
				argument_list+="-F low_gene=$low_gene "
                                ;;
        --mid_gene )           shift
                                mid_gene=$1
				argument_list+="-F mid_gene=$mid_gene "
                                ;;
        --high_gene )           shift
                                high_gene=$1
				argument_list+="-F high_gene=$high_gene "
                                ;;
        --low_cpd )           shift
                                low_cpd=$1
				argument_list+="-F low_cpd=$low_cpd "
                                ;;
        --mid_cpd )           shift
                                mid_cpd=$1
				argument_list+="-F mid_cpd=$mid_cpd "
                                ;;
        --high_cpd )           shift
                                high_cpd=$1
				argument_list+="-F high_cpd=$high_cpd "
                                ;;
        --node_sum )           shift
                                node_sum=$1
				argument_list+="-F node_sum=$node_sum "
                                ;;
        --na_color )           shift
                                na_color=$1
				argument_list+="-F na_color=$na_color "
                                ;;
        --gene_reference )           shift
                                gene_reference=$1
				argument_list+="-F gene_reference=$gene_reference "
                                ;;
        --gene_sample )           shift
                                gene_sample=$1
				argument_list+="-F gene_sample=$gene_sample "
                                ;;
        --gene_compare )           shift
                                gene_compare=$1
				argument_list+="-F gene_compare=$gene_compare "
                                ;;
        --cpd_reference )           shift
                                cpd_reference=$1
				argument_list+="-F cpd_reference=$cpd_reference "
                                ;;
        --cpd_sample )           shift
                                cpd_sample=$1
				argument_list+="-F cpd_sample=$cpd_sample "
                                ;;
        --cpd_compare )           shift
                                cpd_compare=$1
				argument_list+="-F cpd_compare=$cpd_compare "
                                ;;
	--username )          shift
                                username=$1
				argument_list+="-F email=$username "
                                ;;
	--password )          shift
                                password=$1
				argument_list+="-F password=$password "
                                ;;
        -h | --help )           usage
                                exit
                                ;;
        --example )             example_1
                                exit
                                ;;
        *)                      echo "Unknown option $1"
	                        echo "Usage: `basename $0` options [--gene_data] [--cpd_data] [--gene_reference] [--gene_sample] ... [--other options]";
				echo "Try ./pathwayapi.sh --help  for more information."
                                exit
                                ;;
    esac
    shift
done

if [ ! -z $username ] 
then 
  if [ -z "$password" ]
  then
     echo "Password can't be empty..Please re-run the API along with password. Exiting !!!!!" 
     exit 1
  fi
fi 
echo 

function run_api_analysis()
{

   if [ -z $gene_data ] && [ -z $cpd_data ]
   then
     echo  "Either of gene_file or cpd_file should be present to do the analysis"
     echo 
     exit 1
   fi
   eval "curl -i -sS -w '\n'  $argument_list $API_PATH" | sed 's/\\//g' |grep -Ev "HTTP|Cookie|Server|Cache-Control|Content" & PID=$! #simulate a long process
   echo "THIS MAY TAKE A WHILE, PLEASE BE PATIENT WHILE API IS RUNNING..."
   while kill -0 $PID 2> /dev/null
   do
      printf  "#"
         sleep 1
   done
   echo
}

function main()
{
   run_api_analysis
}

main
