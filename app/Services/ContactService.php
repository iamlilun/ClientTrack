<?php

namespace App\Services;

use App\Repositories\ClientRepository;
use App\Repositories\ContactRepository;
use App\Models\Client;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class ContactService
{
    public function __construct(
        protected ClientRepository $clientRepo,
        protected ContactRepository $contactRepo
    ) {}

    /**
     * 查找用戶擁有的客戶
     * @param User $user
     * @param int $clientId
     * @return Client
     * @throws AuthorizationException
     */
    protected function findUserClient(User $user, int $clientId): Client
    {
        $client = $this->clientRepo->findOwnedBy($user, $clientId);

        if (! $client) {
            throw new AuthorizationException('你無權存取這筆資料');
        }

        return $client;
    }

    /**
     * 取得某位客戶的聯絡紀錄列表
     * @param User $user
     * @param int $clientId
     * @return \Illuminate\Database\Eloquent\Collection<Contact>
     */
    public function list(User $user, int $clientId)
    {
        $client = $this->findUserClient($user, $clientId);
        return $this->contactRepo->getAllByClient($client);
    }

    /**
     * 建立一筆聯絡紀錄
     * @param User $user
     * @param int $clientId
     * @param array $data
     * @return Contact
     */
    public function create(User $user, int $clientId, array $data): Contact
    {
        $client = $this->findUserClient($user, $clientId);
        return $this->contactRepo->createForClient($client, $data);
    }

    /**
     * 查看聯絡紀錄細節
     * @param User $user
     * @param int $clientId
     * @param int $id
     * @return Contact
     */
    public function show(User $user, int $clientId, int $id): Contact
    {
        $client = $this->findUserClient($user, $clientId);
        $contact = $this->contactRepo->find($client, $id);

        if (! $contact) {
            throw new AuthorizationException('你無權存取這筆聯絡人');
        }

        return $contact;
    }

    /**
     * 更新聯絡紀錄
     * @param User $user
     * @param int $clientId
     * @param int $id
     * @param array $data
     * @return Contact
     */
    public function update(User $user, int $clientId, int $id, array $data): Contact
    {
        $contact = $this->show($user, $clientId, $id);
        return $this->contactRepo->update($contact, $data);
    }

    /**
     * 刪除聯絡紀錄
     * @param User $user
     * @param int $clientId
     * @param int $id
     * @return void
     */
    public function delete(User $user, int $clientId, int $id): void
    {
        $contact = $this->show($user, $clientId, $id);
        $this->contactRepo->delete($contact);
    }
}
