<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    public function index()
    {
        $history = ChatMessage::where('user_id', Auth::id())
            ->latest()
            ->take(10)
            ->get();
            
        return view('chatbot', compact('history'));
    }

    public function send(Request $request)
    {
        $message = $request->input('message');
        $apiKey = config('services.gemini.key');

        // Lấy 6 tin nhắn gần nhất để làm ngữ cảnh (giống ChatGPT)
        $history = ChatMessage::where('user_id', Auth::id())
            ->latest()
            ->take(6)
            ->get()
            ->reverse();

        $contents = [];
        foreach ($history as $chat) {
            $contents[] = ['role' => 'user', 'parts' => [['text' => $chat->message]]];
            $contents[] = ['role' => 'model', 'parts' => [['text' => $chat->response]]];
        }
        $user = Auth::user();
        $healthProfile = "Thông tin sức khỏe hiện tại của tôi: " . 
            "Nhịp tim: {$user->heart_rate} bpm, " .
            "SpO2: {$user->spo2}%, " .
            "Cân nặng: {$user->weight}kg, " .
            "Chiều cao: {$user->height}cm, " .
            "Uống nước: {$user->water_intake}L, " .
            "Ngủ: {$user->sleep_hours} giờ, " .
            "Bước chân hôm nay: {$user->steps}, " .
            "Calories đã đốt: {$user->calories} kcal.";

        // Thêm tin nhắn hiện tại kèm theo ngữ cảnh sức khỏe thực tế
        $contents[] = [
            'role' => 'user', 
            'parts' => [[
                'text' => "Dưới đây là dữ liệu sức khỏe của tôi từ trang theo dõi. Hãy phân tích và đưa ra chẩn đoán hoặc lời khuyên phù hợp: {$healthProfile}. Câu hỏi hiện tại của tôi là: {$message}"
            ]]
        ];

        $reply = '';
        
        try {
            if ($apiKey) {
                $response = Http::withOptions([
                    'verify' => false,
                ])->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                    'contents' => $contents
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                } else {
                    Log::error('Gemini API Error Body: ' . $response->body());
                }
            }

            if (!$reply) {
                $reply = $this->getMockReply($message);
            }

        } catch (\Exception $e) {
            Log::error('Chatbot Exception: ' . $e->getMessage());
            $reply = $this->getMockReply($message);
        }

        // Lưu vào database
        ChatMessage::create([
            'user_id' => Auth::id(),
            'message' => $message,
            'response' => $reply
        ]);

        return response()->json(['reply' => $reply]);
    }

    /**
     * Trí tuệ nhân tạo dự phòng chuyên nghiệp
     */
    private function getMockReply($text)
    {
        $text = mb_strtolower($text);
        
        if (str_contains($text, 'chào') || str_contains($text, 'hi') || str_contains($text, 'hello')) {
            return "Xin chào! Tôi là HealthSync AI. Tôi có thể giúp gì cho sức khỏe, chế độ ăn uống hoặc lịch trình tập luyện của bạn hôm nay?";
        }
        
        if (str_contains($text, 'ăn') || str_contains($text, 'calo') || str_contains($text, 'dinh dưỡng') || str_contains($text, 'thực đơn')) {
            return "Để duy trì sức khỏe tốt, bạn nên tập trung vào chế độ ăn cân bằng: 50% tinh bột phức hợp, 30% đạm và 20% chất béo tốt. Bạn có muốn tôi gợi ý một thực đơn 1.800kcal cho ngày mai không?";
        }

        if (str_contains($text, 'đẹp trai') || str_contains($text, 'xinh') || str_contains($text, 'đẹp gái') || str_contains($text, 'thế nào')) {
            return "Dựa trên dữ liệu tập luyện chăm chỉ của bạn, tôi tin rằng bạn đang trông rất tuyệt vời và tràn đầy năng lượng! Sự tự tin cũng là một phần quan trọng của sức khỏe tinh thần đấy.";
        }

        if (str_contains($text, 'tập') || str_contains($text, 'gym') || str_contains($text, 'chạy') || str_contains($text, 'thể dục')) {
            return "Tập luyện đều đặn là chìa khóa. Tôi khuyên bạn nên duy trì ít nhất 30 phút vận động mỗi ngày. Hiện tại dữ liệu của bạn cho thấy bạn đang tiến bộ rất tốt trong tuần này!";
        }

        if (str_contains($text, 'ngủ') || str_contains($text, 'mệt') || str_contains($text, 'đau')) {
            return "Giấc ngủ và sự hồi phục rất quan trọng. Bạn nên ngủ đủ 7-8 tiếng và uống đủ 2L nước mỗi ngày. Nếu tình trạng mệt mỏi kéo dài, đừng ngần ngại tham khảo ý kiến bác sĩ nhé.";
        }

        return "Cảm ơn bạn đã chia sẻ. Với tư cách là trợ lý HealthSync, tôi khuyên bạn nên theo dõi sát sao các chỉ số trong Dashboard để thấy được sự thay đổi tích cực. Bạn có câu hỏi cụ thể nào về các chỉ số đó không?";
    }
}
