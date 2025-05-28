<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ClientService;

class ClientController extends Controller
{
    public function __construct(protected ClientService $service) {}

    /**
     * 取得目前使用者的所有客戶
     */
    public function index()
    {
        $clients = $this->service->list(Auth::user());
        return response()->json($clients);
    }


    /**
     * 新增一筆客戶資料
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $client = $this->service->create(Auth::user(), $data);
        return response()->json($client, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $client = $this->service->show(Auth::user(), $id);
        return response()->json($client);
    }


    /**
     * 更新客戶資料
     */
    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $client = $this->service->update(Auth::user(), $id, $data);
        return response()->json($client);
    }

    /**
     * 刪除一筆客戶資料
     */
    public function destroy(int $id)
    {
        $this->service->delete(Auth::user(), $id);
        return response()->json(['message' => '客戶已刪除']);
    }
}
