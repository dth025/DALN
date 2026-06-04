<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('guest_name')->nullable();
            $table->string('guest_avatar')->nullable();
            $table->integer('rating')->nullable();
            $table->text('content');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_admin_reply')->default(false);
            $table->integer('likes_count')->default(0);
            $table->integer('dislikes_count')->default(0);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('feedbacks')->onDelete('cascade');
        });

        Schema::create('feedback_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feedback_id')->constrained('feedbacks')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->string('type'); // 'like' or 'dislike'
            $table->timestamps();
            
            $table->unique(['feedback_id', 'user_id', 'session_id']);
        });

        // Insert initial mock feedbacks
        $now = now();
        
        // Review 1
        $id1 = DB::table('feedbacks')->insertGetId([
            'guest_name' => 'Nguyễn Văn A',
            'guest_avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&h=150&q=80',
            'rating' => 5,
            'content' => 'Giao diện ứng dụng cực kỳ đẹp và mượt mà! Thích nhất chức năng Thực đơn AI rất sát thực tế.',
            'likes_count' => 12,
            'dislikes_count' => 1,
            'created_at' => $now->subDays(5),
            'updated_at' => $now->subDays(5)
        ]);

        // Admin Reply to Review 1
        DB::table('feedbacks')->insert([
            'guest_name' => 'Admin HealthAI',
            'guest_avatar' => 'https://ui-avatars.com/api/?name=Admin+HealthAI&background=6366f1&color=fff',
            'content' => 'Chào bạn A, cảm ơn bạn đã tin tưởng HealthAI. Đội ngũ phát triển luôn nỗ lực hết mình để nâng cao trải nghiệm của bạn!',
            'parent_id' => $id1,
            'is_admin_reply' => true,
            'created_at' => $now->subDays(4),
            'updated_at' => $now->subDays(4)
        ]);

        // Review 2
        $id2 = DB::table('feedbacks')->insertGetId([
            'guest_name' => 'Trần Thị B',
            'guest_avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&h=150&q=80',
            'rating' => 5,
            'content' => 'Chatbot AI trả lời rất thông minh và hữu ích khi mình bị ho khan nửa đêm. Sẽ gia hạn gói Pro!',
            'likes_count' => 8,
            'dislikes_count' => 0,
            'created_at' => $now->subDays(3),
            'updated_at' => $now->subDays(3)
        ]);

        // Admin Reply to Review 2
        DB::table('feedbacks')->insert([
            'guest_name' => 'Admin HealthAI',
            'guest_avatar' => 'https://ui-avatars.com/api/?name=Admin+HealthAI&background=6366f1&color=fff',
            'content' => 'Chào bạn B, cảm ơn bạn đã tin tưởng HealthAI. Trợ lý AI y tế 24/7 luôn sẵn sàng hỗ trợ bạn bất kỳ lúc nào!',
            'parent_id' => $id2,
            'is_admin_reply' => true,
            'created_at' => $now->subDays(2),
            'updated_at' => $now->subDays(2)
        ]);

        // Review 3
        $id3 = DB::table('feedbacks')->insertGetId([
            'guest_name' => 'Lê Hoàng C',
            'guest_avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&h=150&q=80',
            'rating' => 4,
            'content' => 'Thiếu phần kết nối với Smart Watch Garmin của mình. Mong sớm cập nhật.',
            'likes_count' => 3,
            'dislikes_count' => 2,
            'created_at' => $now->subDays(7),
            'updated_at' => $now->subDays(7)
        ]);

        // Admin Reply to Review 3
        DB::table('feedbacks')->insert([
            'guest_name' => 'Admin HealthAI',
            'guest_avatar' => 'https://ui-avatars.com/api/?name=Admin+HealthAI&background=6366f1&color=fff',
            'content' => 'Cảm ơn phản hồi của anh C. Tính năng đồng bộ Garmin đang được thử nghiệm và sẽ phát hành trong phiên bản tới.',
            'parent_id' => $id3,
            'is_admin_reply' => true,
            'created_at' => $now->subDays(6),
            'updated_at' => $now->subDays(6)
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_reactions');
        Schema::dropIfExists('feedbacks');
    }
};
