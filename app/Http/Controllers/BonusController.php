<?php

namespace App\Http\Controllers;

use App\Bonus;
use App\BonusItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BonusController extends Controller
{
    public function index()
	{
		return view('bonus.index', [
			'title' => 'Bonus'
		]);
	}

	public function create()
	{
		return view('bonus.create', [
			'title' => 'Bonus'
		]);
	}

	public function edit($id)
	{
		$bonus = Bonus::find($id)->load('items');
		return view('bonus.edit', [
			'title' => 'Bonus',
			'bonus' => $bonus
		]);
	}

	public function store(Request $request)
	{
		DB::beginTransaction();
		try {
			$bonus = Bonus::create([
				'total' => $request->total
			]);

			foreach ($request->percentage as $key => $percentage) {
				$bonus->items()->create([
					'name' => $request->name[$key],
					'percentage' => $percentage,
					'value' => $request->value[$key]
				]);
			}
			DB::commit();

			return response()->json([
				'status' => true,
				'message' => 'Berhasil Menyimpan Data'
			]);
		} catch (\Throwable $th) {
			DB::rollBack();
			return response()->json([
				'status' => false,
				'message' => $th->getMessage()
			]);
		}
	}

	public function update($id, Request $request)
	{
		DB::beginTransaction();
		try {
			$bonus = Bonus::where('id', $id)->first();
			$bonus->update([
				'total' => $request->total
			]);
			if(count($request->percentage) > 0){
				$bonus->items()->delete();
				foreach ($request->percentage as $key => $percentage) {
					$bonus->items()->create([
						'name' => $request->name[$key],
						'percentage' => $percentage,
						'value' => $request->value[$key]
					]);
				}
			}
			DB::commit();

			return response()->json([
				'status' => true,
				'message' => 'Berhasil Mengupdate Data'
			]);
		} catch (\Throwable $th) {
			DB::rollBack();
			return response()->json([
				'status' => false,
				'message' => $th->getMessage()
			]);
		}
	}

	public function get($id)
	{
		$bonus = Bonus::find($id)->load('items');
		if(!$bonus){
			return response()->json([
				'status' => false,
				'message' => 'Data Tidak Ada'
			]);	
		}
		return response()->json([
			'status' => true,
			'data' => $bonus
		]);
	}

	public function delete($id)
	{
		Bonus::destroy($id);
		return response()->json([
			'status' => true,
			'message' => 'Berhasil Menghapus Data'
		]);
	}

	public function datatables(Request $request)
	{
		$query = Bonus::query();
		$datatables = datatables()->of($query)
		->addIndexColumn()
		->addColumn('bonus_total', function($row){
			return number_format($row->total, 0, ',', '.');
		})
		->addColumn('action', function($row){
			$btn = '';
			$btn .= '<button data-id="'.$row->id.'" class="btn btn-detail btn-sm mr-2 btn-info"><i class="fa fa-eye"></i></button>';
			if(auth()->user()->role == 'admin'){
				$btn .= '<a href="'.route('bonus.edit', $row->id).'" class="btn btn-sm mr-2 btn-warning"><i class="fa fa-pencil"></i></a>';
				$btn .= '<button data-id="'.$row->id.'" class="btn btn-delete btn-sm btn-danger"><i class="fa fa-trash"></i></button>';
			}
			return $btn;
		})
		->rawColumns(['item_percentages', 'item_values', 'action'])
		->toJson();
		return $datatables;
	}
}
