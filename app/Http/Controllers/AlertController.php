<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alert;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductUnit;


class AlertController extends Controller
{
    public function index()
    {
        // Obtener alertas no vistas
        $alerts = Alert::where('status', 0)->latest()->get();
        $alertsView = Alert::where('status', 1)->latest()->get();

        // Obtener productos con bajo stock (stock < 10)
        $lowStockProducts = ProductUnit::where('stock', '<', 10)->get();

        // Pasar las alertas y productos con bajo stock a la vista
        return view('alerts.index', compact('alerts', 'lowStockProducts', 'alertsView'));
    }
    public function markAsRead(Alert $alert)
    {
        // Cambiar el estado de la alerta a "vista"
        $alert->status = 1;  // 1 significa que la alerta ha sido leída
        $alert->save();

        // Redirigir de vuelta con un mensaje de éxito
        return redirect()->route('alerts.index')->with('status', 'Alerta marcada como leída.');
    }


    public function markAsSeen(Alert $alert)
    {
        // Cambiar el estado a 1 (vista)
        $alert->update(['status' => 1]);

        return redirect()->back()->with('success', 'La alerta fue marcada como vista.');
    }
}
