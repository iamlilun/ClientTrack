<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * 取得目前使用者的所有客戶
     */
    public function index()
    {
        $clients = Auth::user()->clients()->latest()->get();
        return response()->json($clients);
    }


    /**
     * 新增一筆客戶資料
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $client = Auth::user()->clients()->create($validated);

        return response()->json($client, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $this->authorizeClient($client);
        return response()->json($client);
    }


    /**
     * 更新客戶資料
     */
    public function update(Request $request, Client $client)
    {
        $this->authorizeClient($client);

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $client->update($validated);

        return response()->json($client);
    }

    /**
     * 刪除一筆客戶資料
     */
    public function destroy(Client $client)
    {
        $this->authorizeClient($client);

        $client->delete();

        return response()->json(['message' => '客戶已刪除']);
    }

    /**
     * 檢查是否為該使用者的客戶
     */
    protected function authorizeClient(Client $client)
    {
        if ($client->user_id !== Auth::id()) {
            abort(403, '你沒有權限操作這筆資料');
        }
    }
}
