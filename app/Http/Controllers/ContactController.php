<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;


class ContactController extends Controller
{
    /**
     * 取得某位客戶的聯絡紀錄列表
     */
    public function index(Client $client)
    {
        $this->authorizeClient($client);

        return response()->json($client->contacts()->latest()->get());
    }


    /**
     * 建立一筆聯絡紀錄
     */
    public function store(Request $request, Client $client)
    {
        $this->authorizeClient($client);

        $validated = $request->validate([
            'contact_type' => 'required|string|max:50',
            'content'      => 'required|string',
            'contacted_at' => 'nullable|date',
        ]);

        $contact = $client->contacts()->create($validated);

        return response()->json($contact, 201);
    }


    /**
     * 查看聯絡紀錄細節
     */
    public function show(Client $client, Contact $contact)
    {
        $this->authorizeContact($client, $contact);

        return response()->json($contact);
    }



    /**
     * 更新聯絡紀錄
     */
    public function update(Request $request, Client $client, Contact $contact)
    {
        $this->authorizeContact($client, $contact);

        $validated = $request->validate([
            'contact_type' => 'required|string|max:50',
            'content'      => 'required|string',
            'contacted_at' => 'nullable|date',
        ]);

        $contact->update($validated);

        return response()->json($contact);
    }

    /**
     * 刪除聯絡紀錄
     */
    public function destroy(Client $client, Contact $contact)
    {
        $this->authorizeContact($client, $contact);
        $contact->delete();
        return response()->json(['message' => '聯絡紀錄已刪除']);
    }

    /**
     * 確認 client 是屬於目前使用者
     */
    protected function authorizeClient(Client $client)
    {
        if ($client->user_id !== Auth::id()) {
            abort(403, '你沒有權限操作這筆資料');
        }
    }

    /**
     * 確認聯絡紀錄是屬於此客戶，且客戶是屬於目前使用者
     */
    protected function authorizeContact(Client $client, Contact $contact)
    {
        if ($client->id !== $contact->client_id) {
            abort(404, '資料錯誤');
        }

        $this->authorizeClient($client);
    }
}
