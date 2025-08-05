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
        Schema::table('bulletins', function (Blueprint $table) {
            // Ajout du champ 'etat' si non existant
            if (!Schema::hasColumn('bulletins', 'etat')) {
                $table->enum('etat', ['non_generé', 'pré_rempli', 'validé'])->default('non_generé');
            }

            // Ajout des index sur 'periode' et 'annee'
            $table->index('periode');
            $table->index('annee');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bulletins', function (Blueprint $table) {
            $table->dropColumn('etat');
            $table->dropIndex(['periode']);
            $table->dropIndex(['annee']);
        });
    }
};
