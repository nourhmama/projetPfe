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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('nom_societe_employeur');
            $table->enum('civilite_representant_employeur', ['Madame', 'Monsieur']);
            $table->string('prenom_nom_representant_employeur');
            $table->string('fonction_representant_employeur');
            $table->enum('civilite_salarie', ['Madame', 'Monsieur']);
            $table->string('prenom_nom_salarie');
            $table->string('adresse_salarie');
            $table->string('emploi_salarie');
            $table->date('date_debut_contrat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
