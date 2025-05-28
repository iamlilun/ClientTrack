<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ContactService;

class ContactController extends Controller
{
    public function __construct(protected ContactService $service) {}

    /**
     * 取得某位客戶的聯絡紀錄列表
     */
    public function index($clientId)
    {
        $contacts = $this->service->list(Auth::user(), $clientId);
        return response()->json($contacts);
    }


    /**
     * 建立一筆聯絡紀錄
     */
    public function store(Request $request, $clientId)
    {
        $data = $request->validate([
            'contact_type' => 'required|string|max:255',
            'content' => 'required|string',
            'contacted_at' => 'nullable|date',
        ]);

        $contact = $this->service->create(Auth::user(), $clientId, $data);
        return response()->json($contact, 201);
    }


    /**
     * 查看聯絡紀錄細節
     */
    public function show($clientId, $id)
    {
        $contact = $this->service->show(Auth::user(), $clientId, $id);
        return response()->json($contact);
    }



    /**
     * 更新聯絡紀錄
     */
    public function update(Request $request, $clientId, $id)
    {
        $data = $request->validate([
            'contact_type' => 'required|string|max:255',
            'content'      => 'required|string',
            'contacted_at' => 'nullable|date',
        ]);

        $contact = $this->service->update(Auth::user(), $clientId, $id, $data);
        return response()->json($contact);
    }

    /**
     * 刪除聯絡紀錄
     */
    public function destroy($clientId, $id)
    {
        $this->service->delete(Auth::user(), $clientId, $id);
        return response()->json(['message' => '聯絡紀錄已刪除']);
    }
}
