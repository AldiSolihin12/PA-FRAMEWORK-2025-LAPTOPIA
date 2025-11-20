<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_details', function (Blueprint $table) {
            if (Schema::hasColumn('product_details', 'engine_type')) {
                $table->dropColumn([
                    'engine_type',
                    'transmission',
                    'capacity',
                    'gasoline',
                    'horsepower',
                ]);
            }

            $table->string('processor')->nullable()->after('description');
            $table->string('graphics')->nullable()->after('processor');
            $table->string('ram')->nullable()->after('graphics');
            $table->string('storage')->nullable()->after('ram');
            $table->string('display')->nullable()->after('storage');
            $table->string('battery')->nullable()->after('display');
            $table->string('weight')->nullable()->after('battery');
            $table->string('ports')->nullable()->after('weight');
            $table->string('operating_system')->nullable()->after('ports');
        });
    }

    public function down(): void
    {
        Schema::table('product_details', function (Blueprint $table) {
            $table->dropColumn([
                'processor',
                'graphics',
                'ram',
                'storage',
                'display',
                'battery',
                'weight',
                'ports',
                'operating_system',
            ]);

            $table->string('engine_type')->default('');
            $table->string('transmission')->default('');
            $table->string('capacity')->default('');
            $table->string('gasoline')->default('');
            $table->string('horsepower')->nullable();
        });
    }
};
