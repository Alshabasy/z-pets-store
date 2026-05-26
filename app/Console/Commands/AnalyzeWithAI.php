<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Gemini;

class AnalyzeWithAI extends Command
{
    protected $signature = 'ai:analyze {type : query or seo}';
    protected $description = 'Analyze queries or SEO using Google Gemini AI';

    public function handle()
    {
        $type = $this->argument('type');
        $apiKey = env('GEMINI_API_KEY');

        if (empty($apiKey)) {
            $this->error('❌ GEMINI_API_KEY is not set in your .env file.');
            return 1;
        }

        $client = Gemini::client($apiKey);

        if ($type === 'query') {
            $prompt = "You are a database optimization expert. Analyze this slow query and suggest specific MySQL indexes to improve performance. Be concise and actionable.\n\nQuery: SELECT * FROM users WHERE email = ? AND status = ? ORDER BY created_at DESC\n\nSlow queries from logs: [{\"query\": \"SELECT * FROM users WHERE email = ?\", \"duration_ms\": 250}]";
        } else {
            $prompt = "You are an SEO expert. Generate optimized meta tags, primary keywords, and a simple internal linking strategy for a pet adoption website. Focus on: adopt pets online, rescue animals, pet adoption near me.";
        }

        $this->info("🤖 Analyzing with Gemini AI (gemini-3.5-flash)...");

        try {
            // Use the correct model for this package version
            $response = $client->generativeModel('gemini-3.5-flash')->generateContent($prompt);
            
            $this->newLine();
            $this->info("✅ Analysis Results:");
            $this->newLine();
            $this->line($response->text());
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            
            // Debug: List available models if there's still an error
            $this->line("\n🔍 Tip: Visit https://ai.google.dev/models/gemini to see current model names.");
            return 1;
        }

        return 0;
    }
}