<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class OllamaTestGenerator extends Command
{
    protected $signature = 'test:generate {class : Fully qualified class name}';
    protected $description = 'Generate tests using local Ollama AI';

    public function handle()
    {
        $class = $this->argument('class');
        
        $filePath = app_path(str_replace('\\', '/', str_replace('App\\', '', $class)) . '.php');
        
        if (!file_exists($filePath)) {
            $this->error("File not found: $filePath");
            return 1;
        }

        $code = file_get_contents($filePath);
        
        $prompt = "You are a Laravel testing expert. Generate complete Pest PHP tests for the following class. Include tests for all public methods, edge cases, and error handling. Return ONLY valid PHP code with <?php tag, no markdown or explanations:\n\n" . $code;

        $this->info("🤖 Generating tests using Ollama (deepseek-r1:8b)...");
        $this->info("⏳ Loading model into memory (this may take a minute on first run)...");
        
        try {
            $response = Http::timeout(300) // 5 minutes for first load
                ->connectTimeout(10)
                ->post('http://localhost:11434/api/generate', [
                    'model' => 'deepseek-r1:8b',
                    'prompt' => $prompt,
                    'stream' => false,
                    'options' => [
                        'num_predict' => 2048,
                        'temperature' => 0.1,
                    ]
                ]);

            $result = $response->json('response');
            
            // Clean up any markdown or explanations
            if (preg_match('/```php\s*(.*?)\s*```/s', $result, $matches)) {
                $testCode = trim($matches[1]);
            } elseif (preg_match('/<\?php.*$/s', $result, $matches)) {
                $testCode = trim($matches[0]);
            } else {
                $testCode = "<?php\n\n// Generated test\n" . $result;
            }
            
            $testPath = base_path('tests/Unit/' . class_basename($class) . 'Test.php');
            
            // Ensure directory exists
            if (!is_dir(dirname($testPath))) {
                mkdir(dirname($testPath), 0755, true);
            }
            
            file_put_contents($testPath, $testCode);
            
            $this->info("✅ Test generated: $testPath");
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            
            // Check if Ollama is actually reachable
            $healthCheck = Http::get('http://localhost:11434/api/tags');
            if ($healthCheck->failed()) {
                $this->error("Ollama is not responding. Run 'ollama serve' in another terminal.");
            } else {
                $this->error("Ollama is running but the model may not be pulled. Run 'ollama pull deepseek-r1:8b'");
            }
            return 1;
        }

        return 0;
    }
}