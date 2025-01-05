<?php

namespace Vamsi\AutoFormRequestData;

use PhpParser\Node\Expr\Cast\String_;

final class AutoReqData
{
    function getMigrationFilesData()
    {
        $migrationsPath = realpath('../database/migrations');
        if ($migrationsPath === false) {
            die("Migrations directory not found.\n");
        }

        $migrationFiles = glob($migrationsPath . '/*.php');
        $tableDetails = [];

        foreach ($migrationFiles as $migrationFile) {
            $fileContent = file_get_contents($migrationFile);

            $schemaBlocks = explode('Schema::create', $fileContent);
            array_shift($schemaBlocks);

            foreach ($schemaBlocks as $block) {
                preg_match("/\(['\"]([\w\d_]+)['\"]/", $block, $tableMatch);
                if (empty($tableMatch[1])) {
                    continue;
                }
                $tableName = $tableMatch[1];

                $columns = [];
                $callbackStart = strpos($block, '{');
                $callbackEnd = strrpos($block, '}');

                if ($callbackStart !== false && $callbackEnd !== false) {
                    $callbackBody = substr($block, $callbackStart + 1, $callbackEnd - $callbackStart - 1);

                    $lines = explode("\n", $callbackBody);
                    foreach ($lines as $line) {
                        $line = trim($line);

                        if (preg_match("/->([\w]+)\(['\"]([\w\d_]+)['\"]/", $line, $columnMatch)) {
                            $columns[] = [
                                'name' => $columnMatch[2],
                                'type' => $columnMatch[1],
                            ];
                        }
                    }
                }

                $tableDetails[] = [
                    'table' => $tableName,
                    'columns' => $columns,
                ];
            }
        }

        echo "\nFinal Table Details:\n";
        print_r($tableDetails);

        $this->generateFormRequestFiles($tableDetails);
    }

    function generateFormRequestFiles($tableDetails)
    {
        $typeToRules = [
            'string' => ['string', 'max:255'],
            'longText' => ['string'],
            'text' => ['string'],
            'integer' => ['integer'],
            'unsignedTinyInteger' => ['integer', 'min:0'],
            'unsignedInteger' => ['integer', 'min:0'],
            'timestamp' => ['date'],
        ];

        $template = <<<EOT
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class {{CLASS_NAME}} extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            {{RULES}}
        ];
    }
}
EOT;

        foreach ($tableDetails as $table) {
            $className = ucfirst($table['table']) . 'Request';
            $basePath = realpath(__DIR__ . '/../'); // Adjust this to point to your project root
            $requestsFolderPath = $basePath . '/app/Http/Requests';
            if (!is_dir($requestsFolderPath)) {
                if (!mkdir($requestsFolderPath, 0755, true)) {
                    die("Failed to create Requests folder: $requestsFolderPath");
                }
            }
            $filePath = $basePath . "/app/Http/Requests/{$className}.php";
            $rules = $this->generateRules($table['columns'], $typeToRules);
            $fileContent = str_replace(['{{CLASS_NAME}}', '{{RULES}}'], [$className, $rules], $template);
            file_put_contents($filePath, $fileContent);
            echo "Generated file: {$filePath}\n";
        }
    }

    function generateRules($columns, $typeToRules)
    {
        $rulesArray = [];
        $ignoredColumns = ['id'];
        $requiredColumns = ['email', 'emailid', 'email_id', 'mail_id', 'mail', 'mobile', 'mobile_no', 'phone_no', 'phone', 'contact_no'];

        foreach ($columns as $column) {
            $columnName = $column['name'];
            $columnType = $column['type'];
            if (in_array($columnName, $ignoredColumns)) {
                continue;
            }
            $rules = $typeToRules[$columnType] ?? ['nullable'];
            if (in_array($columnName, $requiredColumns)) {
                array_unshift($rules, 'required');
            }
            if (preg_match('/email|mail/i', $columnName)) {
                $rules[] = 'email';
            }
            $rulesArray[] = "'{$columnName}' => '" . implode('|', $rules) . "'";
        }
        return implode(",\n            ", $rulesArray);
    }
}

$var = new AutoReqData();
$var->getMigrationFilesData();
