<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Models\Medicine;
use App\Models\Stock;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Throwable;

class StockController extends Controller
{
  public function index()
  {
    $stocks = Stock::all();
    return view('dashboard.master.stock.index', compact('stocks'));
  }

  public function create()
  {
    $suppliers = Supplier::all();
    $medicines = Medicine::all();
    return view('dashboard.master.stock.create', compact('suppliers', 'medicines'));
  }

  public function store(StoreStockRequest $request)
  {
    try {
      Stock::create($request->all());
      return redirect()->route('master.stock.index')->with('success', 'Data berhasil disimpan!');
    } catch (Throwable $th) {
      return redirect()->back()
        ->withErrors(['message' => ['Terjadi kesalahan saat menyimpan data!', $th->getMessage()]]);
    }
  }

  public function show(Stock $stock)
  {
    try {
      $medicine = Medicine::where('id', $stock->medicine_id)->firstOrFail();
      return view('dashboard.master.stock.show', compact('stock', 'medicine'));
    } catch (Throwable $th) {
      return redirect()->back()
        ->withErrors(['message' => ['Terjadi kesalahan saat mengambil data!', $th->getMessage()]]);
    }
  }

  public function edit(Stock $stock)
  {
    $suppliers = Supplier::all();
    $medicines = Medicine::all();
    return view('dashboard.master.stock.edit', compact('stock', 'suppliers', 'medicines'));
  }

  public function update(UpdateStockRequest $request, Stock $stock)
  {
    try {
      $stock->update($request->all());
      return redirect()->route('master.stock.index')->with('success', 'Data berhasil diedit!');
    } catch (Throwable $th) {
      return redirect()->back()
        ->withErrors(['message' => ['Terjadi kesalahan saat mengedit data!', $th->getMessage()]]);
    }
  }

  public function destroy(Stock $stock, Request $request)
  {
    try {
      throw_if(!confirmPassword($request->password), 'Password yang anda masukkan salah!');
      Stock::destroy($stock->id);
      return redirect()->route('master.stock.index')->with('success', 'Data stok berhasil dihapus!');
    } catch (Throwable $th) {
      return redirect()->back()
        ->withErrors(['message' => ['Terjadi kesalahan saat menghapus data!', $th->getMessage()]]);
    }
  }
}
