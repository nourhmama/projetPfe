<?php

namespace App\Http\Controllers\Api;

use App\Models\Contract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ContractController extends Controller
{
    public function index()
    {
        try {
            $contracts = Contract::all();
            return response()->json(['contracts' => $contracts], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des contrats: ' . $e->getMessage()], 500);
        }
    }

    public function create()
    {
        return response()->json(['message' => 'Page de création de contrat'], 200);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nom_societe_employeur' => 'required|string',
                'civilite_representant_employeur' => 'required|in:Madame,Monsieur',
                'prenom_nom_representant_employeur' => 'required|string',
                'fonction_representant_employeur' => 'required|string',
                'civilite_salarie' => 'required|in:Madame,Monsieur',
                'prenom_nom_salarie' => 'required|string',
                'adresse_salarie' => 'required|string',
                'emploi_salarie' => 'required|string',
                'date_debut_contrat' => 'required|date',
            ]);

            Contract::create($validatedData);
            return response()->json(['message' => 'Contrat créé avec succès'], 201);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Erreur lors de la création du contrat: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue: ' . $e->getMessage()], 500);
        }
    }

    public function show(Contract $contract)
    {
        return response()->json(['contract' => $contract], 200);
    }

    public function edit(Contract $contract)
    {
        return response()->json(['message' => 'Page d\'édition de contrat'], 200);
    }

    public function update(Request $request, Contract $contract)
    {
        try {
            $validatedData = $request->validate([
                'nom_societe_employeur' => 'required|string',
                'civilite_representant_employeur' => 'required|in:Madame,Monsieur',
                'prenom_nom_representant_employeur' => 'required|string',
                'fonction_representant_employeur' => 'required|string',
                'civilite_salarie' => 'required|in:Madame,Monsieur',
                'prenom_nom_salarie' => 'required|string',
                'adresse_salarie' => 'required|string',
                'emploi_salarie' => 'required|string',
                'date_debut_contrat' => 'required|date',
            ]);

            $contract->update($validatedData);
            return response()->json(['message' => 'Contrat mis à jour avec succès'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Erreur lors de la mise à jour du contrat: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Contract $contract)
    {
        try {
            $contract->delete();
            return response()->json(['message' => 'Contrat supprimé avec succès'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Erreur lors de la suppression du contrat: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue: ' . $e->getMessage()], 500);
        }
    }
}
