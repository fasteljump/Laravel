<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table): void {
            $table->string('client_validation_status')->nullable()->after('billing_type');
            $table->text('client_validation_comment')->nullable()->after('client_validation_status');
            $table->timestamp('client_validated_at')->nullable()->after('client_validation_comment');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table): void {
            $table->dropColumn(['client_validation_status', 'client_validation_comment', 'client_validated_at']);
        });
    }
};
