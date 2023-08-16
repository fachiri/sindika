@extends('layouts.master', [
    'breadcrumbs' => [
        'Dashboard' => route('dashboard.index'),
        'Master' => '',
        'Supplier' => '',
    ],
])

@section('title', 'Supplier')

@section('main')
  <x-card.table id="supplier" :create-route="route('master.supplier.create')" :columns="['Nama Supplier', 'Alamat', 'No. Hp']">
    @foreach ($suppliers as $supplier)
      <x-table.row :$loop :detail-route="route('master.supplier.show', $supplier->id)" :delete-route="''">
        <td>{{ $supplier->name }}</td>
        <td>{{ $supplier->address }}</td>
        <td>{{ $supplier->phone }}</td>
      </x-table.row>
    @endforeach
  </x-card.table>
@endsection
