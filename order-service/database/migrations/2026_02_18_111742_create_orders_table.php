<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration { 
    /**
    * Run the migrations.
    */ 
    public function up(): void { 
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('order_id')->primary()->default(DB::raw('gen_random_uuid()')); 
            $table->uuid('customer_id'); 
            $table->uuid('restaurant_id'); 
            $table->json('items'); 
            $table->string('address'); 
            $table->decimal('total', 10, 2)->default(0); 
            $table->string('status')->default('PENDING'); 
            $table->timestamp('placed_at')->useCurrent(); 
            $table->timestamps(); 
        }); 
    } 
    /** 
    * Reverse the migrations. 
    */ 
    
    public function down(): void { 
        Schema::dropIfExists('orders'); 
    } 
};