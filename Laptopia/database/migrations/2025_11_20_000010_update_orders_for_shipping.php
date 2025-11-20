<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'pickup_name')) {
            // Schema already uses shipping columns (fresh installations).
            return;
        }

        DB::statement("ALTER TABLE orders MODIFY status VARCHAR(20) NOT NULL DEFAULT 'pending'");
        DB::statement('ALTER TABLE orders CHANGE pickup_name recipient_name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE orders CHANGE pickup_phone recipient_phone VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE orders CHANGE pickup_email recipient_email VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE orders CHANGE pickup_date shipped_at DATETIME NULL');

        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('confirmed_at')->nullable()->after('status');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            $table->string('shipping_address')->nullable()->after('recipient_email');
            $table->string('shipping_city')->nullable()->after('shipping_address');
            $table->string('shipping_postal_code', 20)->nullable()->after('shipping_city');
            $table->string('shipping_method')->nullable()->after('shipping_postal_code');
            $table->string('tracking_number')->nullable()->after('shipping_method');
            $table->decimal('shipping_cost', 12, 2)->default(0)->after('subtotal');
        });

        DB::statement('UPDATE orders SET status = CASE status WHEN "ongoing" THEN "pending" WHEN "arrived" THEN "delivered" ELSE status END');
    }

    public function down(): void
    {
        if (! Schema::hasColumn('orders', 'recipient_name') || Schema::hasColumn('orders', 'pickup_name')) {
            return;
        }

        DB::statement('UPDATE orders SET status = CASE status WHEN "delivered" THEN "arrived" ELSE "ongoing" END');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'confirmed_at',
                'delivered_at',
                'shipping_address',
                'shipping_city',
                'shipping_postal_code',
                'shipping_method',
                'tracking_number',
                'shipping_cost',
            ]);
        });

        DB::statement('ALTER TABLE orders CHANGE shipped_at pickup_date DATE NULL');
        DB::statement('ALTER TABLE orders CHANGE recipient_email pickup_email VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE orders CHANGE recipient_phone pickup_phone VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE orders CHANGE recipient_name pickup_name VARCHAR(255) NOT NULL');
        DB::statement("ALTER TABLE orders MODIFY status ENUM('ongoing','arrived') NOT NULL DEFAULT 'ongoing'");
    }
};
