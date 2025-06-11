<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use League\CommonMark\CommonMarkConverter;

class ChatbotController extends Controller
{
    protected $apiKey;
    
    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        if (empty($this->apiKey)) {
            Log::error('GEMINI_API_KEY tidak ditemukan di file .env');
        } else {
            Log::info('GEMINI_API_KEY tersedia: ' . substr($this->apiKey, 0, 5) . '...');
        }
    }
    
    public function showChat()
    {
        return view('chatbot.index');
    }
    
    public function processMessage(Request $request)
    {
        try {
            Log::info('Received message request: ' . $request->input('message'));
            
            $message = $request->input('message');
            
            if ($this->isDataAnalysisRequest($message)) {
                Log::info('Request identified as data analysis');
                $analysisDetails = $this->extractAnalysisDetails($message);
                Log::info('Analysis details: ' . json_encode($analysisDetails));
                
                $data = $this->fetchDataFromDatabase($analysisDetails);
                Log::info('Fetched data count: ' . count($data));
                
                $response = $this->analyzeWithGemini($data, $message, $analysisDetails);
                
                return response()->json([
                    'response' => $response,
                    'isAnalysis' => true
                ], 200);
            } else {
                Log::info('Request identified as regular chat');
                $response = $this->chatWithGemini($message);
                
                return response()->json([
                    'response' => $response,
                    'isAnalysis' => false
                ], 200);
            }
        } catch (\Exception $e) {
            Log::error('Error in processMessage: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'error' => 'An error occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    protected function isDataAnalysisRequest($message)
    {
        // Detect data analysis keywords
        $keywords = ['analisis data', 'analisa', 'data', 'statistik', 'tabel', 'database', 'keuangan', 'laporan', 'pemasukkan', 'pengeluaran', 'tabungan'];
        
        // Personal indicators
        $personalIndicators = ['saya', 'aku', 'milik saya', 'punya saya', 'milikku', 'my', 'mine', '-ku'];
        
        $isAnalysisRequest = false;
        foreach ($keywords as $keyword) {
            if (stripos($message, $keyword) !== false) {
                $isAnalysisRequest = true;
                break;
            }
        }
        
        if ($isAnalysisRequest) {
            // Check if it's a personal request
            foreach ($personalIndicators as $indicator) {
                if (stripos($message, $indicator) !== false) {
                    return true;
                }
            }
        }
        
        return $isAnalysisRequest;
    }   
    
    protected function extractAnalysisDetails($message)
    {
        $tables = [
            'pemasukkans' => ['pemasukan', 'income', 'pendapatan', 'masuk', 'pemasukkan'],
            'pengeluarans' => ['keluar', 'expense', 'biaya', 'bayar', 'pengeluaran'],
            'tabungans' => ['savings', 'goal', 'target', 'simpan', 'tabungan'],
        ];
        
        $detectedTables = [];
        
        foreach ($tables as $tableName => $aliases) {
            if (stripos($message, $tableName) !== false) {
                if (Schema::hasTable($tableName)) {
                    $detectedTables[] = $tableName;
                }
                continue;
            }

            foreach ($aliases as $alias) {
                if (stripos($message, $alias) !== false) {
                    if (Schema::hasTable($tableName)) {
                        $detectedTables[] = $tableName;
                        break;
                    }
                }
            }
        }

        // Extract user-specific conditions
        $isPersonalRequest = $this->isPersonalRequest($message);
        $userId = $isPersonalRequest ? $this->getCurrentUserId() : null;
        
        // Parse other conditions (time period, categories)
        $conditions = $this->parseConditions($message);
        
        // Add user ID to conditions if personal request and user is authenticated
        if ($userId) {
            $conditions['user_id'] = $userId;
        }
        
        if (empty($detectedTables)) {
            // Default to the pengeluarans table for expense analysis
            if (stripos($message, 'pengeluaran') !== false) {
                $detectedTables[] = 'pengeluarans';
            } else {
                // Otherwise default to pemasukkans
                $detectedTables[] = 'pemasukkans';
            }
        }
        
        if (count($detectedTables) > 1) {
            return [
                'tables' => $detectedTables,
                'type' => 'combined',
                'conditions' => $conditions,
                'isPersonal' => $isPersonalRequest
            ];
        } else {
            return [
                'table' => $detectedTables[0],
                'type' => 'single',
                'conditions' => $conditions,
                'isPersonal' => $isPersonalRequest
            ];
        }
    }

    protected function isPersonalRequest($message)
    {
        $personalIndicators = ['saya', 'aku', 'milik saya', 'punya saya', 'milikku', '-ku'];
        
        foreach ($personalIndicators as $indicator) {
            if (stripos($message, $indicator) !== false) {
                return true;
            }
        }
        
        return false;
    }

    protected function getCurrentUserId()
    {
        $userId = Auth::id();

        if($userId) {
            return $userId;
        }
        
        return null;
    }
    
    protected function parseConditions($message)
    {
        $conditions = [];
        
        // Ekstrak periode waktu
        if (preg_match('/dari\s+(\w+)\s+(?:hingga|sampai)\s+(\w+)/i', $message, $matches)) {
            $conditions['period_from'] = $matches[1];
            $conditions['period_to'] = $matches[2];
        }
        
        // Ekstrak kategori atau jenis
        if (preg_match('/kategori\s+(\w+)/i', $message, $matches)) {
            $conditions['category'] = $matches[1];
        }
        
        return $conditions;
    }
    
    protected function fetchDataFromDatabase($details)
    {
        if ($details['type'] === 'combined') {
            // Handle multiple tables
            $result = [];
            foreach ($details['tables'] as $table) {
                $query = DB::table($table);
                $this->applyConditions($query, $details['conditions'], $table);
                $result[$table] = $query->limit(500)->get()->toArray();
            }
            return $result;
        } else {
            // Handle single table
            $query = DB::table($details['table']);
            $this->applyConditions($query, $details['conditions'], $details['table']);
            return $query->limit(1000)->get()->toArray();
        }
    }

    protected function applyConditions($query, $conditions, $tableName = null)
    {
        // Apply date range conditions if the column exists
        if (isset($conditions['period_from']) && isset($conditions['period_to'])) {
            if ($this->hasColumn($tableName, 'tanggal')) {
                $query->whereBetween('tanggal', [
                    $this->parseDate($conditions['period_from']),
                    $this->parseDate($conditions['period_to'])
                ]);
            }
        }
        
        // Apply category filter if the column exists
        if (isset($conditions['category'])) {
            if ($this->hasColumn($tableName, 'kategori')) {
                $query->where('kategori', 'like', '%' . $conditions['category'] . '%');
            }
        }
        
        // Apply user filter if personal request
        if (isset($conditions['user_id'])) {
            if ($tableName === 'users') {
                // For users table, filter by the primary key 'id'
                $query->where('id', $conditions['user_id']);
            } else {
                // For other tables, check if they have a user_id foreign key
                if ($this->hasColumn($tableName, 'user_id')) {
                    $query->where('user_id', $conditions['user_id']);
                }
            }
        }
        
        return $query;
    }

    // Helper method to check if a column exists in a table
    protected function hasColumn($tableName, $columnName)
    {
        if (!$tableName || !$columnName) {
            return false;
        }
        
        // Cache column information to avoid repeated database calls
        static $columnCache = []; 
        
        try {
            if (!isset($columnCache[$tableName])) {
                $columnCache[$tableName] = Schema::getColumnListing($tableName);
            }
            
            return in_array($columnName, $columnCache[$tableName]);
        } catch (\Exception $e) {
            Log::error("Error checking columns for table {$tableName}: " . $e->getMessage());
            return false;
        }
    }
    
    protected function parseDate($monthName)
    {
        $months = [
            'januari' => '01',
            'februari' => '02',
            'maret' => '03',
            'april' => '04',
            'mei' => '05',
            'juni' => '06',
            'juli' => '07',
            'agustus' => '08',
            'september' => '09',
            'oktober' => '10',
            'november' => '11',
            'desember' => '12'
        ];
        
        $year = date('Y');
        
        foreach ($months as $name => $num) {
            if (stripos($monthName, $name) !== false) {
                return "$year-$num-01";
            }
        }
        
        return date('Y-m-d'); // Default ke hari ini
    }
    
    protected function analyzeWithGemini($data, $userQuestion, $analysisDetails)
    {
        $apiUrl = 'https://generativelanguage.googleapis.com/v1/models/gemini-2.0-flash:generateContent?key=' . $this->apiKey;
    
        $userContext = $analysisDetails && isset($analysisDetails['isPersonal']) && $analysisDetails['isPersonal'] 
        ? "Data ini adalah milik pengguna yang bertanya." 
        : "Data ini dari database sistem.";
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($apiUrl, [
            'contents' => [
                'parts' => [
                    [
                        'text' => "Kamu adalah asisten AI bernama FinAi yang berempati dan ahli analisis keuangan. Berikut panduan untuk merespons:

                        Pengguna bertanya: \"$userQuestion\"
                        $userContext

                        Jika pengguna hanya ingin bercerita atau meminta saran tentang masalah keuangan, maka hindari untuk menganalisis data, hanya berikan saran tentang masalah yang dihadapi pengguna

                        Berikut adalah data dari database MySQL:\n" . json_encode($data, JSON_PRETTY_PRINT) . "

                        Berikan analisis dengan format berikut:

                        1. Ringkasan Situasi:
                        - Tunjukkan pemahaman tentang kondisi keuangan pengguna
                        - Gunakan bahasa yang empatik dan mendukung
                        - Identifikasi poin-poin utama dari data

                        2. Detail Analisis:
                        - Jelaskan tren penting yang ditemukan
                        - Sorot perubahan signifikan
                        - Bandingkan dengan periode sebelumnya (jika ada)
                        - Identifikasi pola pengeluaran atau pemasukan

                        3. Rekomendasi Praktis:
                        - Berikan minimal 3 saran konkret yang dapat diterapkan
                        - Fokus pada perbaikan yang realistis
                        - Sertakan langkah-langkah implementasi

                        4. Motivasi dan Dukungan:
                        - Apresiasi upaya yang sudah dilakukan
                        - Berikan dorongan positif
                        - Tekankan bahwa perubahan adalah proses

                        Penting:
                        - Gunakan bahasa yang sederhana dan mudah dipahami
                        - Hindari jargon keuangan yang rumit
                        - Jangan gunakan format Markdown
                        - Buat analisis yang memotivasi tanpa menghakimi
                        - Fokus pada solusi, bukan masalah"
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.4,
                'maxOutputTokens' => 2048
            ]
        ]);
        
        $result = $response->json();
        
        if (isset($result['error'])) {
            Log::error('Gemini API error: ' . json_encode($result['error']));
            return "Error dari API Gemini: " . ($result['error']['message'] ?? 'Unknown error');
        }
        
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            $text = $result['candidates'][0]['content']['parts'][0]['text'];
            $text = preg_replace('/\*(.*?)\*/', '$1', $text); // Remove *text*
            $text = preg_replace('/\*\*(.*?)\*\*/', '$1', $text); // Remove **text**
            return $text;
        } else {
            return "Maaf, terjadi kesalahan dalam analisis data. Silakan coba lagi dengan permintaan yang berbeda.";
        }
    }
    
    protected function chatWithGemini($message)
    {
        $apiUrl = 'https://generativelanguage.googleapis.com/v1/models/gemini-2.0-flash:generateContent?key=' . $this->apiKey;
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($apiUrl, [
            'contents' => [
                'parts' => [
                    [
                        'text' => "Kamu adalah asisten AI bernama FinAi yang berempati dan memahami masalah keuangan. Berikut panduan untuk merespons:

                        1. Jika pengguna bercerita tentang masalah keuangan:
                        - Dengarkan dengan empati
                        - Berikan dukungan moral
                        - Tawarkan saran praktis yang relevan
                        - Hindari menganalisis data kecuali diminta

                        2. Jika pengguna meminta saran keuangan:
                        - Berikan tips yang praktis dan dapat diterapkan
                        - Gunakan bahasa yang sederhana dan motivatif
                        - Fokus pada solusi jangka pendek dan panjang
                        - Tunjukkan pemahaman atas situasi mereka

                        3. Jika pengguna menanyakan tentang data atau analisis:
                        - Arahkan mereka untuk bertanya spesifik tentang:
                            * Analisis pemasukkan
                            * Analisis pengeluaran
                            * Analisis tabungan
                        - Jelaskan bahwa mereka bisa mendapatkan insight dari data mereka

                        Pengguna mengatakan: \"$message\"

                        Berikan respons yang sesuai dengan konteks pembicaraan. Pastikan menggunakan bahasa yang ramah dan membangun. Jangan gunakan format Markdown seperti tanda bintang (*) atau garis bawah (_) dalam teks."
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.8,
                'maxOutputTokens' => 1024
            ]
        ]);
        
        $result = $response->json();
        
        if (isset($result['error'])) {
            Log::error('Gemini API error: ' . json_encode($result['error']));
            return "Error dari API Gemini: " . ($result['error']['message'] ?? 'Unknown error');
        }
        
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            $text = $result['candidates'][0]['content']['parts'][0]['text'];
            $text = preg_replace('/\*(.*?)\*/', '$1', $text); // Remove *text*
            $text = preg_replace('/\*\*(.*?)\*\*/', '$1', $text); // Remove **text**
            return $text;
        } else {
            return "Maaf, saya tidak dapat memproses permintaan Anda saat ini. Silakan coba lagi nanti.";
        }
    }
}