<?php

use Illuminate\Database\Seeder;

class PathwayRequiredDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /**
         * This function takes the data from the data file and upload the data
         * into tables so that evary time deployed into a new system can be done
         * by just running the simple seeder command
         *
         *
         */



        //seeding the pathway id details into table for the seeder data files present in public folder

        //if data already present place a backup of the data into public folder


        try
        {
            $this->command->info('analysis Table seed started!');

            $analysisSeedContents = File::get(public_path()."/data/database_seed/analysis_seed_data.csv");
            $lines  = explode("\n",$analysisSeedContents);

            if(sizeof($lines)>0 )
            {
                DB::table('analysisErrors')->delete();
                DB::table('analysis')->delete();
            }
            foreach($lines as $line)
            {
                $columns = explode(";",$line);
                $this->command->info($line);
                if(sizeof($columns) > 0)
                DB::table('analysis')->insert(
                    array('analysis_id' => $columns[0] ,
                        'id'=> $columns[1],
                        'arguments'=> $columns[2],
                        'ip_add'=> $columns[5],
                        'analysis_type'=> $columns[3],
                        'analysis_origin' => $columns[6],
                        'created_at' =>$columns[4]));
            }
            $this->command->info('analysis Table seed end!');


        }
        catch (Illuminate\Filesystem\FileNotFoundException $exception)
        {
            die("analysis_seed_data.csv file doesn't exist");
        }



        try
        {
            $this->command->info('analysisErrors Table seed started!');

            $analysisErrorsSeedContents = File::get(public_path()."/data/database_seed/analysis_error_seed_data.csv");

            $lines  = explode("\n",$analysisErrorsSeedContents);

            if(sizeof($lines)>0)
            {
                DB::table('analysisErrors')->delete();
            }
                foreach($lines as $line)
                {

                    $columns = explode(";",$line);
                    if(sizeof($columns) > 0)
                    $this->command->info($line);
                    DB::table('analysisErrors')->insert(
                        array('analysis_id' => $columns[1] ,
                        'error_string'=> $columns[2],
                        'created_at' =>$columns[3])
                    );
                }
            $this->command->info('analysisErrors Table seed end!');
        }
        catch (Illuminate\Filesystem\FileNotFoundException $exception)
        {
            die("analysis_error_seed_data.csv file doesn't exist");
        }

        try
        {
            $this->command->info('users Table seed started!');
            $usersSeedContents = File::get(public_path()."/data/database_seed/users_seed_data.csv");

            $lines  = explode("\n",$usersSeedContents);

            if(sizeof($lines)>0)
            {
                DB::table('users')->delete();
            }
            foreach($lines as $line)
            {
                $columns = explode(";",$line);
                $this->command->info($line);
             if(sizeof($columns) > 0)
                DB::table('users')->insert(
                    array('id' => $columns[0] ,
                        'name'=> $columns[1],
                        'organisation' =>$columns[6],
                        'email' => $columns[2] ,
                        'password'=> $columns[3],
                        'remember_token' =>$columns[4],
                        'created_at' =>$columns[5],
                        'updated_at' =>$columns[6])
                );
            }
            $this->command->info('users Table seed end!');

        }
        catch (Illuminate\Filesystem\FileNotFoundException $exception)
        {
            die("users_seed_data.csv file doesn't exist");
        }


        try
        {
            $this->command->info('biocStatistics Table seed started!');
            $biocStatisticsSeedContents = File::get(public_path()."/data/database_seed/biocstatistics_seed_data.csv");

            $lines  = explode("\n",$biocStatisticsSeedContents);

            if(sizeof($lines)>0)
            {
                DB::table('biocStatistics')->delete();
            }
            foreach($lines as $line)
            {
                $columns = explode(";",$line);
                $this->command->info($line);
                if(sizeof($columns) > 0)
                    DB::table('biocStatistics')->insert(
                        array('month' => $columns[0] ,
                            'year'=> $columns[1],
                            'number_of_unique_ip' =>$columns[2],
                            'number_of_downloads' => $columns[3])
                    );
            }
            $this->command->info('biocStatistics Table seed end!');
        }
        catch (Illuminate\Filesystem\FileNotFoundException $exception)
        {
            die("biocstatistics_seed_data.csv file doesn't exist");
        }

        try
        {
            $compoundSeedContents = File::get(public_path()."/data/database_seed/compound_seed_data.csv");



        }
        catch (Illuminate\Filesystem\FileNotFoundException $exception)
        {
            die("compound_seed_data.csv file doesn't exist");
        }

        try
        {
            $this->command->info('gene Table seed started!');
            $geneSeedContents = File::get(public_path()."/data/database_seed/gene_seed_data.csv");
            $lines  = explode("\n",$geneSeedContents);

            if(sizeof($lines)>0)
            {
                DB::table('gene')->delete();
            }
            foreach($lines as $line)
            {
                $columns = explode(";",$line);
                $this->command->info($line);
                if(sizeof($columns) > 0)
                    DB::table('gene')->insert(
                        array('gene_id' => $columns[0])
                    );
            }
            $this->command->info('gene Table seed end!');


        }
        catch (Illuminate\Filesystem\FileNotFoundException $exception)
        {
            die("gene_seed_data.csv file doesn't exist");
        }

        try
        {
            $this->command->info('pathway Table seed start!');
            $pathwaySeedContents = File::get(public_path()."/data/database_seed/pathway_seed_data.csv");
            $lines  = explode("\n",$pathwaySeedContents);

            if(sizeof($lines)>0)
            {
                DB::table('pathway')->delete();
                DB::table('speciesPathwayMatch')->delete();
            }
            foreach($lines as $line)
            {
                $columns = explode(";",$line);
                $this->command->info($line);
                if(sizeof($columns) > 1)
                    DB::table('pathway')->insert(
                        array('pathway_id' => $columns[0],
                            'pathway_desc' => $columns[1],
                            'created_at' => $columns[2],)
                    );
            }
            $this->command->info('gene Table seed end!');


        }
        catch (Illuminate\Filesystem\FileNotFoundException $exception)
        {
            die("pathway_seed_data.csv file doesn't exist");
        }

        try
        {
            $this->command->info('species Table seed start!');
            $speciesSeedContents = File::get(public_path()."/data/database_seed/species_seed_data.csv");
            $lines  = explode("\n",$speciesSeedContents);

            if(sizeof($lines)>0)
            {

                DB::table('species')->delete();
            }
            foreach($lines as $line)
            {
                $columns = explode(";",$line);
                $this->command->info($line);
                if(sizeof($columns) > 1)
                    DB::table('species')->insert(
                        array('species_id' => $columns[0],
                            'species_desc' => $columns[1],
                            'disease_index_exist' => $columns[3],
                            'species_common_name' => $columns[5],
                            'created_at' => $columns[2]));
            }
            $this->command->info('species Table seed end!');



        }
        catch (Illuminate\Filesystem\FileNotFoundException $exception)
        {
            die("species_seed_data.csv file doesn't exist");
        }

        try
        {
            $this->command->info('speciesPathwayMatchseed Table seed end!');
            $speciesPathwayMatchSeedContents = File::get(public_path()."/data/database_seed/speciesPathwayMatch_seed_data.csv_1");
            $lines  = explode("\n",$speciesPathwayMatchSeedContents);


            foreach($lines as $line)
            {
                $columns = explode(";",$line);
                $this->command->info($line);
                if(sizeof($columns) > 1)
                    DB::table('speciesPathwayMatch')->insert(
                        array('species_id' => str_replace(" ", "", $columns[1]),
                            'pathway_id' => str_replace(" ", "", $columns[2]),
                            'created_at' => str_replace(" ", "", $columns[3])));
            }
            $this->command->info('speciesPathwayMatchseed Table seed end!');


        }
        catch (Illuminate\Filesystem\FileNotFoundException $exception)
        {
            die("speciesPathwayMatch_seed_data.csv file doesn't exist");
        }



    }
}
