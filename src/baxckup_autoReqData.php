<?php

namespace Vamsi\AutoFormRequestData;

final class AutoReqData
{

    // function generateRequestDataForExisitingMigrations()
    // {

    //     /*
    //       get all migration files 
    //       get table name for each file <> for example 'users' 
    //       make a request file with name usersRequestData 
    //       get all columns in an array with defaults available 

    //       example the collection array should be like this 
    //       users -> name 
    //                     -> datatype: varchar 
    //                     -> can_be_null: 
    //                     -> has_default:  
    //             -> email
    //                     -> 
    //       based on datatype rules should be applied 
    //     */
    //     $migrationsPath = realpath('../database/migrations'); // Adjust this path to match your Laravel project structure
    //     if ($migrationsPath === false) {
    //         die("Migrations directory not found.\n");
    //     }

    //     $migrationFiles = glob($migrationsPath . '/*.php'); // Get all migration files
    //     $tableDetails = [];
    //     $tableNames = []; // Separate array to store just table names

    //     foreach ($migrationFiles as $migrationFile) {
    //         $fileContent = file_get_contents($migrationFile); // Read the file content

    //         // Match all occurrences of Schema::create and extract table names
    //         preg_match_all("/Schema::create\(['\"](\w+)['\"]/", $fileContent, $matches);

    //         if (!empty($matches[1])) {
    //             $tableNames = array_merge($tableNames, $matches[1]); // Merge all table names into the array
    //         }

    //         // Match all occurrences of Schema::create with callback to extract columns
    //         preg_match_all("/Schema::create\(['\"](\w+)['\"],\s*function\s*\((\$\w+)\)/", $fileContent, $matchesWithCallback);

    //         if (!empty($matchesWithCallback[1])) {
    //             foreach ($matchesWithCallback[1] as $index => $tableName) {
    //                 $callbackVariable = $matchesWithCallback[2][$index]; // The variable name in the callback (e.g., $table)

    //                 // Extract the block of code inside the callback
    //                 $callbackPattern = "/Schema::create\(['\"]{$tableName}['\"],\s*function\s*\({$callbackVariable}\)\s*{(.*?)}/s";
    //                 preg_match($callbackPattern, $fileContent, $callbackMatches);

    //                 $columns = [];
    //                 if (!empty($callbackMatches[1])) {
    //                     // Extract all column definitions
    //                     preg_match_all("/{$callbackVariable}->(\w+)\(['\"]?([\w\d_]+)['\"]?.*?\)/", $callbackMatches[1], $columnMatches);

    //                     if (!empty($columnMatches[1])) {
    //                         foreach ($columnMatches[1] as $colIndex => $columnType) {
    //                             $columns[] = [
    //                                 'name' => $columnMatches[2][$colIndex],
    //                                 'type' => $columnType,
    //                             ];
    //                         }
    //                     }
    //                 }

    //                 // Add table details to the result
    //                 $tableDetails[] = [
    //                     'table' => $tableName,
    //                     'columns' => $columns,
    //                 ];
    //             }
    //         }
    //     }

    //     // Output the results
    //     echo "Table Names:\n";
    //     var_dump($tableNames);

    //     echo "\nTable Details:\n";
    //     var_dump($tableDetails);
    // }

    // function extractMigrationDetails()
    // {
    //     $migrationsPath = realpath('../database/migrations'); // Adjust this path as needed
    //     if ($migrationsPath === false) {
    //         die("Migrations directory not found.\n");
    //     }

    //     $migrationFiles = glob($migrationsPath . '/*.php'); // Get all migration files
    //     $tableDetails = [];

    //     foreach ($migrationFiles as $migrationFile) {
    //         $fileContent = file_get_contents($migrationFile); // Read the file content

    //         // Match all occurrences of Schema::create
    //         preg_match_all(
    //             "/Schema::create\(['\"](\w+)['\"],\s*function\s*\((.*?)\)/",
    //             $fileContent,
    //             $matches
    //         );

    //         if (!empty($matches[1])) {
    //             foreach ($matches[1] as $index => $tableName) {
    //                 $callbackVariable = trim($matches[2][$index]); // Extract the variable (e.g., $table)

    //                 // Extract the block of code inside the callback
    //                 $callbackPattern = "/Schema::create\(['\"]{$tableName}['\"],\s*function\s*\({$callbackVariable}\)\s*{(.*?)}/s";
    //                 preg_match($callbackPattern, $fileContent, $callbackMatches);
    //                 $callbackVariable = trim($matches[2][$index]); // Extract the variable (e.g., Blueprint $table)
    //                 $callbackVariable = preg_replace('/^Blueprint\s+/', '', $callbackVariable); // Remove "Blueprint"
    //                 print_r($callbackPattern);
    //                 exit;
    //                 $columns = [];

    //                 if (!empty($callbackMatches[1])) {
    //                     // Refine the regex to extract column definitions robustly
    //                     preg_match_all(
    //                         "/{$callbackVariable}->([\w]+)\(['\"]?([\w\d_]+)['\"]?(.*?)\);/",
    //                         $callbackMatches[1],
    //                         $columnMatches,
    //                         PREG_SET_ORDER
    //                     );

    //                     foreach ($columnMatches as $match) {
    //                         $columns[] = [
    //                             'name' => $match[2], // Column name
    //                             'type' => $match[1], // Data type (e.g., string, integer)
    //                             'attributes' => trim($match[3]), // Additional attributes (nullable, default, etc.)
    //                         ];
    //                     }
    //                 }

    //                 // Add table details to the result
    //                 $tableDetails[] = [
    //                     'table' => $tableName,
    //                     'columns' => $columns,
    //                 ];
    //             }
    //         }
    //     }

    //     // Output the results
    //     echo "\nTable Details:\n";
    //     print_r($tableDetails);
    // }

    function extractMigrationDetailsWithoutTokens()
    {
        $migrationsPath = realpath('../database/migrations'); // Adjust this path as needed
        if ($migrationsPath === false) {
            die("Migrations directory not found.\n");
        }

        $migrationFiles = glob($migrationsPath . '/*.php'); // Get all migration files
        $tableDetails = [];

        foreach ($migrationFiles as $migrationFile) {
            $fileContent = file_get_contents($migrationFile);

            // Split content by "Schema::create"
            $schemaBlocks = explode('Schema::create', $fileContent);
            array_shift($schemaBlocks); // Remove any content before the first Schema::create

            foreach ($schemaBlocks as $block) {
                // Extract table name
                preg_match("/\(['\"]([\w\d_]+)['\"]/", $block, $tableMatch);
                if (empty($tableMatch[1])) {
                    continue; // Skip if no table name found
                }
                $tableName = $tableMatch[1];

                // Extract column definitions
                $columns = [];
                $callbackStart = strpos($block, '{');
                $callbackEnd = strrpos($block, '}');

                if (
                    $callbackStart !== false && $callbackEnd !== false
                ) {
                    $callbackBody = substr($block, $callbackStart + 1, $callbackEnd - $callbackStart - 1);

                    // Split by lines to process column definitions
                    $lines = explode("\n", $callbackBody);
                    foreach ($lines as $line) {
                        $line = trim($line);

                        // Match column definitions
                        if (preg_match("/->([\w]+)\(['\"]([\w\d_]+)['\"]/", $line, $columnMatch)) {
                            $columns[] = [
                                'name' => $columnMatch[2], // Column name
                                'type' => $columnMatch[1], // Column type
                            ];
                        }
                    }
                }

                // Add table and columns to the details
                $tableDetails[] = [
                    'table' => $tableName,
                    'columns' => $columns,
                ];
            }
        }

        // Output the results
        echo "\nFinal Table Details:\n";
        print_r($tableDetails);



        /*
        *
         Next Steps Create a Req Class with Artisan Command With table name 
         
         No add the columns into rules array with another function  a switch case to set the data types and defaults 
        */
    }

    function generateRequestDataALongWithMigration() {}

    function generateRequestDataForASingleFile() {}

    function addValidations() {}

    function generateDTOs() {}
}
$var = new AutoReqData();
$result = $var->extractMigrationDetailsWithoutTokens();
