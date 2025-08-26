<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tryout;
use App\Models\TryoutQuestion;
use Illuminate\Http\Request;

class TryoutAIImportController extends Controller
{
    public function create(Tryout $tryout)
    {
        return inertia('Admin/Tryouts/Questions/AIImport', [
            'tryout' => $tryout,
        ]);
    }

    public function generate(Request $request, Tryout $tryout)
    {
        $data = $request->validate([
            'category' => 'required|string',
            'count' => 'required|integer|min:1|max:50',
            'difficulty' => 'nullable|string',
        ]);

        $apiKey = config('services.sumopod_ai.key', env('SUMOPOD_AI_KEY'));
        $baseUrl = config('services.sumopod_ai.base_url', env('SUMOPOD_AI_BASE_URL', 'https://ai.sumopod.com/v1'));
        if (!$apiKey) {
            return inertia('Admin/Tryouts/Questions/AIImport', [
                'tryout' => $tryout,
                'preview' => [],
                'form' => $data,
                'error' => 'SUMOPOD_AI_KEY belum diset di .env',
            ]);
        }

        $prompt = "Buat {$data['count']} soal pilihan ganda untuk tryout kategori '{$data['category']}'";
        if (!empty($data['difficulty'])) {
            $prompt .= ", tingkat kesulitan: {$data['difficulty']}";
        }
        $prompt .= ". Output HARUS JSON array: [{\\n  \"question\": string,\\n  \"options\": [string,string,string,string,string],\\n  \"answer\": 1..5\\n}] tanpa teks lain.";

        $client = new \GuzzleHttp\Client(['base_uri' => $baseUrl, 'timeout' => 45]);
        try {
            $resp = $client->post('chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 4000,
                    'temperature' => 0.5,
                ],
            ]);
        } catch (\Throwable $e) {
            return inertia('Admin/Tryouts/Questions/AIImport', [
                'tryout' => $tryout,
                'preview' => [],
                'form' => $data,
                'error' => 'Gagal panggil AI: ' . $e->getMessage(),
            ]);
        }

        $json = json_decode((string) $resp->getBody(), true);
        if (!isset($json['choices'][0]['message']['content'])) {
            return inertia('Admin/Tryouts/Questions/AIImport', [
                'tryout' => $tryout,
                'preview' => [],
                'form' => $data,
                'error' => 'Respons AI tidak sesuai.',
            ]);
        }
        $content = $json['choices'][0]['message']['content'] ?? '';
        $content = trim($content);
        if (str_starts_with($content, '```')) {
            $content = preg_replace('/^```[a-zA-Z]*\n|```$/m', '', $content);
        }
        if (!str_starts_with(ltrim($content), '[')) {
            if (preg_match('/\[(?:[^\[\]]++|(?R))*\]/s', $content, $m)) {
                $content = $m[0];
            }
        }

        $items = json_decode($content, true);
        if (!is_array($items)) {
            return inertia('Admin/Tryouts/Questions/AIImport', [
                'tryout' => $tryout,
                'preview' => [],
                'form' => $data,
                'error' => 'Output AI tidak valid.',
            ]);
        }

        // ensure include flag default true
        $items = array_map(function($it){ if(!isset($it['include'])) $it['include']=true; return $it; }, $items);

        return inertia('Admin/Tryouts/Questions/AIImport', [
            'tryout' => $tryout,
            'preview' => $items,
            'form' => $data,
            'error' => null,
        ]);
    }

    public function confirm(Request $request, Tryout $tryout)
    {
        $payload = $request->validate([
            'items' => 'required|array'
        ]);
        $items = $payload['items'];
        $created = 0;
        foreach ($items as $it) {
            if (isset($it['include']) && !$it['include']) continue;
            $q = trim($it['question'] ?? '');
            $opts = $it['options'] ?? [];
            $ans = (int) ($it['answer'] ?? 0);
            if (!$q || count($opts) < 2 || $ans < 1 || $ans > 5) continue;
            TryoutQuestion::create([
                'tryout_id' => $tryout->id,
                'question' => $q,
                'option_1' => $opts[0] ?? null,
                'option_2' => $opts[1] ?? null,
                'option_3' => $opts[2] ?? null,
                'option_4' => $opts[3] ?? null,
                'option_5' => $opts[4] ?? null,
                'answer'   => $ans,
            ]);
            $created++;
        }
        return redirect()->route('admin.tryouts.questions.index', $tryout->id)->with('success', "AI mengimpor {$created} soal.");
    }
}
