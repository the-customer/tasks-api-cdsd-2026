<?php

use App\Enums\Enums\TaskVisibility;
use App\Enums\TaskStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->enum('status', TaskStatus::cases())->default(TaskStatus::PENDING->value)->index();
            $table->enum('visibility', TaskVisibility::cases())->default(TaskVisibility::PRIVATE ->value)->index();
            $table->timestamp('archived_at')->nullable()->index();
            $table->timestamps();

            // $table->check('status')->in(['pending', 'in_progress', 'completed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
