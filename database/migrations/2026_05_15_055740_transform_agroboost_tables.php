<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop old tables if they exist (clean up recent placeholder migrations)
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('inventory_requests');

        // 1. Orders table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_amount', 15, 2);
            $table->string('payment_status')->default('pending'); // pending, paid
            $table->string('order_status')->default('pending'); // pending, accepted, packed, shipped, delivered
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // 2. Order Items table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('crop_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->decimal('price', 10, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        // 3. Farmer Requests table
        Schema::create('farmer_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->string('request_type'); // seeds, fertilizer, tools, irrigation, equipment
            $table->string('item_name');
            $table->decimal('quantity', 10, 2);
            $table->text('description')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, delivered
            $table->text('admin_response')->nullable();
            $table->timestamps();
        });

        // 4. Crop Listings table
        Schema::create('crop_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crop_id')->constrained()->onDelete('cascade');
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->decimal('quantity_available', 10, 2);
            $table->decimal('price_per_unit', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Tasks table improvements
        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tasks', 'priority')) {
                $table->string('priority')->default('medium'); // low, medium, high
            }
            if (!Schema::hasColumn('tasks', 'status')) {
                $table->string('status')->default('pending'); // pending, in_progress, completed
            }
        });

        // 6. Inventory Items improvements
        Schema::table('inventory_items', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory_items', 'low_stock_threshold')) {
                $table->decimal('low_stock_threshold', 10, 2)->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropColumn('low_stock_threshold');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['priority', 'status']);
        });

        Schema::dropIfExists('crop_listings');
        Schema::dropIfExists('farmer_requests');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
