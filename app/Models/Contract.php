<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_societe_employeur',
        'civilite_representant_employeur',
        'prenom_nom_representant_employeur',
        'fonction_representant_employeur',
        'civilite_salarie',
        'prenom_nom_salarie',
        'adresse_salarie',
        'emploi_salarie',
        'date_debut_contrat',
    ];
}
