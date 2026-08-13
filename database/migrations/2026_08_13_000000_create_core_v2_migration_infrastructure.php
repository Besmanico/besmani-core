<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('migration_batches', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('status', 30)->default('pending')->index();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('code_commit_sha', 64)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('legacy_entity_maps', function (Blueprint $table): void {
            $table->id();
            $table->string('source_system', 50);
            $table->string('source_table', 100);
            $table->string('source_id', 191);
            $table->string('target_entity_type', 100);
            $table->unsignedBigInteger('target_id');
            $table->foreignId('migration_batch_id')->nullable()->constrained('migration_batches')->nullOnDelete();
            $table->string('source_checksum', 128)->nullable();
            $table->timestamp('migrated_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('status', 30)->default('mapped')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['source_system', 'source_table', 'source_id', 'target_entity_type'], 'legacy_map_source_target_unique');
            $table->index(['target_entity_type', 'target_id']);
        });

        Schema::create('migration_errors', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('migration_batch_id')->nullable()->constrained('migration_batches')->nullOnDelete();
            $table->string('source_system', 50);
            $table->string('source_table', 100);
            $table->string('source_id', 191)->nullable();
            $table->string('error_code', 100)->index();
            $table->text('error_message');
            $table->json('payload_snapshot_json')->nullable();
            $table->string('resolution_status', 30)->default('open')->index();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('migration_reconciliation', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('migration_batch_id')->constrained('migration_batches')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('entity_type', 100);
            $table->unsignedBigInteger('source_count')->default(0);
            $table->unsignedBigInteger('target_count')->default(0);
            $table->unsignedBigInteger('matched_count')->default(0);
            $table->unsignedBigInteger('error_count')->default(0);
            $table->string('checksum', 128)->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->unique(['migration_batch_id', 'entity_type']);
        });

        Schema::create('identity_merge_candidates', function (Blueprint $table): void {
            $table->id();
            $table->string('source_a_system', 50);
            $table->string('source_a_table', 100);
            $table->string('source_a_id', 191);
            $table->string('source_b_system', 50);
            $table->string('source_b_table', 100);
            $table->string('source_b_id', 191);
            $table->unsignedTinyInteger('match_score');
            $table->json('reasons_json');
            $table->string('status', 30)->default('pending_review')->index();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->unique(['source_a_system', 'source_a_table', 'source_a_id', 'source_b_system', 'source_b_table', 'source_b_id'], 'identity_candidate_pair_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('identity_merge_candidates');
        Schema::dropIfExists('migration_reconciliation');
        Schema::dropIfExists('migration_errors');
        Schema::dropIfExists('legacy_entity_maps');
        Schema::dropIfExists('migration_batches');
    }
};
