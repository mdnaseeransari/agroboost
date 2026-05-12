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
        // Drop the journal_entries table
        Schema::dropIfExists('journal_entries');

        // Update roles in users table
        DB::table('users')->where('role', 'viewer')->update(['role' => 'buyer']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert roles
        DB::table('users')->where('role', 'buyer')->update(['role' => 'viewer']);
        
        // Note: Recreating journal_entries is not worth it here as it was a cleanup
    }
};
